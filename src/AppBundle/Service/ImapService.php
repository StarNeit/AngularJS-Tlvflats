<?php
/**
 * Created by IntelliJ IDEA.
 * User: hp1
 * Date: 04.10.2015
 * Time: 17:54
 */

namespace AppBundle\Service;


use AppBundle\Entity\EmailAccount;
use Monolog\Logger;

class ImapService
{
    /**
     * @var Logger
     */
    private $logger;


    /**
     * ImapService constructor.
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;

    }

    public function openImap(EmailAccount $account) {
        $imap = imap_open('{' . $account->getServerHost() . ':' . $account->getServerPort(). '/imap/ssl/novalidate-cert/norsh}INBOX', $account->getServerLogin(), $account->getServerPassword());

        if (!$imap) {
            $this->logger->addError("Failed to connect to mailbox " . imap_last_error());
            throw new \Exception("Failed to connect to mailbox " . imap_last_error());
        }

        return $imap;
    }

    public function findMail(EmailAccount $account, $search)
    {
        $imap = $this->openImap($account);
        $searchresults = imap_search($imap, $search);

        if (empty($searchresults)) {
            $this->logger->addError("Was not able to find mail for " . $search);
            return array("error" => "Confirmation not found", "need_retry" => true, "need_report" => false);
        }

        $this->logger->addDebug("Found " . count($searchresults) . " matching letters for " . $search);

        $imap_search = $searchresults[0];
        $imap_fetchstructure = imap_fetchstructure($imap, $imap_search);

        $i = 0;

        if ($imap_fetchstructure->subtype != 'PLAIN') {

            $this->logger->addDebug("found multipart letter for " . $search);

            foreach ($imap_fetchstructure->parts as $part) {

                $i++;

                if ($part->subtype == 'PLAIN') {
                    break;
                }

                $this->logger->addError("Should not get there for " . $search);
            }

            $body = imap_fetchbody($imap, $imap_search, $i);

            if ($part->encoding == 4)
                $body = quoted_printable_decode($body);
            elseif ($part->encoding == 3)
                $body = base64_decode($body);
        } else {
            $body = imap_body($imap, $imap_search);
        }

        $this->close($imap);
        return $body;
    }

    public function close($imapCon)
    {
        imap_close($imapCon);
    }
}