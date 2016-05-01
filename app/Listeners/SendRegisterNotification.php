<?php

namespace App\Listeners;

use App\Events\RegisterEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class SendRegisterNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  RegisterEvent  $event
     * @return void
     */
    public function handle(RegisterEvent $event)
    {
        $email = $event->email;

        $data = $event->fname;
        $sent = Mail::send(
            'emails.register_notification', 
            ['name' => $data],
            function($message) use ($email, $data) {
                $message->from('noreply@eGlasanje.com', 'e-Glasanje');
                $message->to($email, $data);
                $message->subject('e-Glasanje: Registracija');
            }
        );

        if( ! $sent) dd("something wrong");
        dd("send");
    }
}
