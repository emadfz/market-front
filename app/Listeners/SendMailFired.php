<?php

namespace App\Listeners;

use App\Events\SendMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class SendMailFired {

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SendMail  $event
     * @return void
     */
    public function handle(SendMail $event) {

        //echo "<pre>";print_r($event);die;
        $data['html'] = $event->mailData['emailContent'];
        Mail::send('front.email_templates.render_email', $data, function ($message) use ($event) {
            $message->from($event->mailData['fromEmail'], $event->mailData['fromName']);
            $message->to($event->mailData['toEmail'], $event->mailData['toName']);
            $message->subject($event->mailData['emailSubject']);
            //$message->setBody($event->mailData['emailContent']);
        });
    }
}
