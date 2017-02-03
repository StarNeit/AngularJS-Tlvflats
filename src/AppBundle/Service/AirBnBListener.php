<?php
/**
 * Created by IntelliJ IDEA.
 * User: hp1
 * Date: 04.10.2015
 * Time: 17:53
 */

namespace AppBundle\Service;


use AppBundle\Entity\Booking;
use AppBundle\Entity\DeferredTask;
use AppBundle\Entity\EmailAccount;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;

class AirBnBListener implements NotifyListenerInterface
{
    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var ImapService
     */
    private $imap;
    private $beds24;
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var
     */
    private $recheck;


    /**
     * AirBnBListener constructor.
     */
    public function __construct(Logger $logger, \Swift_Mailer $mailer, ImapService $imap, Beds24 $beds24, EntityManager $em, $recheck)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
        $this->imap = $imap;
        $this->beds24 = $beds24;
        $this->em = $em;
        $this->recheck = $recheck;
    }

    public function notify(Booking $booking, $status)
    {
        $api_resp = $booking->getApiResponce();
        $code = $booking->getApiReference();
        /** @var EmailAccount $email */
        foreach($booking->getRoom()->getProperty()->getOwner()->getEmails() as $email) {
            try {
                $body = $this->imap->findMail($email, 'BODY "Confirmation Code" BODY "' . $code . '"');
            } catch(\Exception $ex) {
                $this->logger->debug("Failed to get body from ".$email->getEmail().". ".$ex->getMessage());
            }
            if (!empty($body) && !$this->isError($body))
                break;
        }
        if(!isset($body))
            return "Failed to process booking: " . $booking->getBookId();
        if($this->isError($body)) {
            if($this->isNeedRetry($body)) {
                $this->scheduleRetry($booking, $status);
            }
            return "Failed to process booking: ".$body["error"];
        }
        $parsed=$this->parseBody($body, $booking->getBookId());
        if($this->isError($body)) {
            if($this->isNeedRetry($body)) {

            }

            return "Failed to process booking: " . $booking->getBookId();
        }

        return $this->updateBooking($api_resp, $parsed, $booking->getRoom()->getProperty()->getOwner()->getApiKey(),$booking->getRoom()->getProperty()->getPropKey());
    }

    public function interestedIn(Booking $booking, $status)
    {
        $api_resp = $booking->getApiResponce();
        $this->logger->addInfo("Not intersting yet in anything");
        return $booking->getReferer()=="Airbnb.com" && $status == "new" && empty($api_resp['invoice']);
    }

    private function parseBody($body, $bookId)
    {
        $res = array();
        
        $matched=false;

        if (preg_match('/Guests\s+(\d+)/si', $body, $guests)) {
            $res['guests'] = intval($guests[1]);
            $this->logger->addDebug("Found guests: ".$res['guests']." for booking: ".$bookId);
            $matched=true;
        }

        if (preg_match('/Guest Pays\s+\$(\d+)/si', $body, $accomodations)) {
            $res['accomodations'] = intval($accomodations[1]);
            $this->logger->addDebug("Found accomodations: ".$res['accomodations']." for booking: ".$bookId);
            $matched=true;
        }

        if (preg_match('/Cleaning Fees\s+\$(\d+)/si', $body, $cleaning_fees)) {
            $res['cleaning_fees'] = intval($cleaning_fees[1]);
            $this->logger->addDebug("Found cleaning fees: ".$res['cleaning_fees']." for booking: ".$bookId);
            $matched=true;
        }

        if (preg_match('/Airbnb Fees\s+-\$(\d+)/si', $body, $airbnb_fees)) {
            $res['service_fee'] = -intval($airbnb_fees[1]);
            $this->logger->addDebug("Found service fee: ".$res['service_fee']." for booking: ".$bookId);
            $matched=true;
        }

        if (preg_match('/You earn\s+\$(\d+)/si', $body, $total)) {
            $res['total'] = intval($total[1]);
            $this->logger->addDebug("Found total: ".$res['total']." for booking: ".$bookId);
            $matched=true;
        }
        
        if(!$matched) {
            $this->logger->addError("Nothing matched. format changed? For ".$bookId);
            return array("error"=>"Could not parse", "need_retry"=>false, "need_report"=>true);
        }

        return $res;

    }

    private function isError($body)
    {
        if(empty($body["error"])) {
            return false;
        }

        return true;
    }

    private function isNeedRetry($body)
    {
        if(empty($body["need_retry"])) {
            return false;
        }

        return true;
    }

    private function updateBooking($api_resp, $res, $apiKey, $propKey)
    {
        if (isset($res['total'])) {

            $api_resp['price'] = $res['total'];

            $accomodations = $res['total'];

            if (isset($res['cleaning_fees'])) {
                $accomodations = $accomodations - $res['cleaning_fees'];
            }

            if (!isset($api_resp['invoice'])) {
                $api_resp['invoice'] = array();
            }

            array_push($api_resp['invoice'],
                array("description" => "Accomodations",
                    "qty" => 1,
                    "price" => $accomodations,
                ));
        }

        if (isset($res['guests'])) {
            $api_resp['numAdult'] = $res['guests'];
        }

        if (isset($res['cleaning_fees'])) {
            if (!isset($api_resp['invoice'])) {
                $api_resp['invoice'] = array();
            }

            array_push($api_resp['invoice'],
                array("description" => "Cleaning fees",
                    "qty" => 1,
                    "price" => $res['cleaning_fees'],
                ));
        }

        $modified_booking = $api_resp;
        $modified_booking['authentication'] = array('apiKey' => $apiKey, 'propKey' => $propKey);

        return $this->beds24->setBooking($modified_booking);
    }

    private function scheduleRetry(Booking $booking, $status)
    {
        if(empty($this->recheck)) return;
        $task = new DeferredTask();

        $executeAt = new \DateTime();
        $executeAt=$executeAt->add(new \DateInterval($this->recheck));
        $task->setExecuteAt($executeAt);

        $task->setExecuteCommand("airbnb_listener::notify");
        $task->setParams("Booking(".$booking->getId()."),".$status);
        $this->em->persist($task);
        $this->em->flush();
    }
}