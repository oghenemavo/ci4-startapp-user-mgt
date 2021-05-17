<?php

namespace Ecosystem\Authentication\Libraries;

use Config\Services;
use Ecosystem\Authentication\Models\MailClient;

class MailerLib {

    protected $dispatcher = 'app_mailer';
    protected $client;

    public function __construct()
    {
        $this->client = new MailClient();
    }

    /**
     * Send a mail with with set dispatcher
     *
     * @param array $view           presentation data (html, text)
     * @param array $address        addresses for sender and recipient
     * @param array $data           dynamic mail sending input data
     * @param array $attachment     file attachment 
     * @return bool                 true or false
     */
    public function send_mail(array $view, array $address, array $data, array $attachment = [])
    {
        $parser = Services::parser();

        $html = $view['html'];
        $text = $view['text'];

        $html_email = $parser->setData($view['data'])->renderString($html);
        $text_email = $parser->setData($view['data'])->renderString($text);

        $data['html'] = $html_email;
        $data['text'] = $text_email;

        return $this->set_dispatcher($address, $data, $attachment);
    }

    /**
     * Set Mail sending Library
     *
     * @param array $data           Mail sending data
     * @return void
     */

    /**
     * Set Mail sending Library
     *
     * @param array $address        address data
     * @param array $data           email content data
     * @param array $attachment     attachment data
     * @return bool
     */
    protected function set_dispatcher($address, $data, $attachment) 
    {
        $dispatcher = $this->get_dispatcher() ?? $this->dispatcher;
        switch ($dispatcher) {
            case 'mail_gun':
                $dispatch = ''; // mail gun dispatcher method
                break;
            
            default:
                $dispatch = service('ciMailerLib')->dispatch($address, $data, $attachment);
                break;
        }
        return $dispatch;
    }

    /**
     * Get Mail sending Library
     *
     * @return void
     */
    protected function get_dispatcher()
    {
        $current_client = $this->client->where('is_active', '1')->orderBy('updated_at', 'DESC')->first();
        return $current_client ? $current_client->client_slug : $this->dispatcher;
    }
}