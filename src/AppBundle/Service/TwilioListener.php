<?php

/**
 * Created by Mohsin Kabir.
 * Date: 10.11.2015
 */

namespace AppBundle\Service;

use Twilio\Twilio;

class TwilioListener {

    /**
     *
     * @var Twilio Account sid
     */
    private $accountSid;

    /**
     * @var Twilio Auth token
     */
    private $authToken;
    private $client;
    private $smsFrom;

    function __construct() {
        $this->accountSid = 'AC1b7c55f8a89f7aa08a020fd3ce71d53a';
        $this->authToken = '3edf7e7e01185ca928d95def8d5f5d10';
        $this->smsFrom = "+14098777775";
        $this->client = new Services_Twilio($this->accountSid, $this->authToken);
    }

    /**
     * 
     * @param type $sms_to  - sms to should be with country code e.g "+8801534406051"; here +880 is bangladesh country code
     * @param type $message
     */
    public function sendSms($sms_to, $message) {
        $message = $this->client->account->messages->create(array(
            'To' => $sms_to,
            'From' => $this->smsFrom,
            'Body' => $message,
        ));
    }

}
