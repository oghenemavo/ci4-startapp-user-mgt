<?php

namespace Ecosystem\Authentication\Libraries;

use Config\Services;

class CIMailerLib {

    /**
     * Sends out a mail
     *
     * @param array $address            addresses for sender and recipient
     * @param array $data               dynamic mail sending input data
     * @param array $attachment         file attachment 
     * @return bool                     true or false
     */
    public function dispatch(array $address, array $data, array $attachment = []) {
        $email = Services::email(); // CI email library

        //process
        $email->setFrom($address['from'], $address['from_name']); // sender (email, name)
        $email->setTo($address['to']); // receipent (email)
        $email->setSubject($data['subject']); // email subject
        $email->setMessage($data['html']); // html message
        $email->setAltMessage($data['text']); // text message

        if (count($attachment)) {
            foreach($attachment as $value) {
                $email->attach($value);
            }
        }

        return $email->send(); // send mail
    }

    /**
     * Sends out a mail and get response information on the execution
     *
     * @param array $address            addresses for sender and recipient
     * @param array $data               dynamic mail sending input data
     * @param array $attachment         file attachment 
     * @return bool                     true or false
     */
    public function debug_dispatch(array $address, array $data, array $attachment = []) {
        $email = Services::email();

        //process
        $email->setFrom($address['from'], $address['from_name']); // sender (email, name)
        $email->setTo($address['to']); // receipent (email)
        $email->setSubject($data['subject']); // email subject
        $email->setMessage($data['html']); // html message
        $email->setAltMessage($data['text']); // text message

        if (count($attachment)) {
            foreach($attachment as $value) {
                $email->attach($value);
            }
        }

        $email->send(false); // send mail
        return $email->printDebugger(['headers']); // get response information
    }
}