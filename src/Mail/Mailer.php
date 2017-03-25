<?php


namespace Uno\Mail;


use Swift_Message;


abstract class Mailer
{

    protected $message;

    public function __construct()
    {
        $this->message = Swift_Message::newInstance();
    }

    public function subject($subject = 'Your Subject' )
    {
        $this->message->setSubject($subject);

        return $this;
    }

    public function from($from = null )
    {
        $from = is_null($from) ? config('mail.from') : $from;

        $this->message->setFrom($from);

        return $this;
    }

    public function to($to = [] )
    {
        $this->message->setTo($to);

        return $this;
    }

    public function attach($files = [] )
    {
        $this->message->attach(Swift_Attachment::fromPath('my-document.pdf'));

        return $this;
    }

    public function message($view)
    {
        $this->message
            ->setBody('Here is the message itself')
            ->addPart('<q>Here is the message itself</q>', 'text/html');
    }

}