<?php

namespace App\Services;

use MailchimpMarketing\ApiClient;

class MailchimpService
{
    protected $mailchimp;

    public function __construct()
    {
        $this->mailchimp = new \MailchimpMarketing\ApiClient();
        $this->mailchimp->setConfig([
            'apiKey' => config('mailchimp.api_key'),
            'server' => config('mailchimp.server_prefix'),
        ]);

    }

    public function sendEmail($from, $to, $subject, $body)
    {
        try {
            // Create the campaign (email)
            $campaign = $this->mailchimp->campaigns->create([
                'type' => 'regular',
                'recipients' => [
                    'list_id' => config('mailchimp.list_id'), // You still need a list ID. Can be any existing list
                ],
                'settings' => [
                    'subject_line' => $subject,
                    'from_name' => $from,
                    'reply_to' => $from,
                    'to_name' => $to,
                ],
            ]);

            dd($campaign);

            // Set the content of the campaign (HTML content)
            $this->mailchimp->campaigns->setContent($campaign['id'], [
                'html_content' => $body,
            ]);

            // Send the campaign
            $this->mailchimp->campaigns->send($campaign['id']);

            return response()->json(['success' => true, 'message' => 'Email sent successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
