<?php


namespace Uno\Mail;


class Mail extends Mailer
{

    public function send()
    {
        $this->message->send();
    }
}