<?php

namespace System\Utils;

class Emailer
{
    public $from;
    public $to;
    public $subject;
    public $message;

    public function __construct($from, $to, $subject, $message)
    {
        $this->from = $from;
        $this->to = $to;
        $this->subject = $subject;
        $this->message = $message;
    }

    public function sendTextMail()
    {
        $headers = 'From: '.$this->from.'<'.$this->from.">\n";
        $headers .= 'Reply-To: '.$this->to.'<'.$this->to.'>';
        $mailSent = @mail($this->to, $this->subject, $this->message, $headers);

        return $mailSent;
    }

    public function sendHTMLMail()
    {
        $headers = 'MIME-Version: 1.0'."\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
        $headers .= 'From: '.'<'.$this->from.">\n";
        $headers .= 'Reply-To: '.$this->to.'<'.$this->to.'>';
        $mailSent = @mail($this->to, $this->subject, $this->message, $headers);

        return $mailSent;
    }
}
