<?php

namespace AppBundle\Services;

class MailerService
{
    private $mailer;
    private $container;
    private $strFrom;
    private $strTo;
    private $strSubject;

    public function __construct($container)
    {

        $this->container = $container;
    }

    public function setTo($strTo = false)
    {
        $this->strTo = ($strTo === false) ? $this->strTo : $strTo;
    }

    public function setSubject($strSubject = false)
    {
        $this->strSubject = ($strSubject === false) ? $this->strSubject : $strSubject;
    }
    
    public function setFrom($strFrom = false)
    {
        $this->strFrom = ($strFrom === false) ? $this->strFrom : $strFrom;
    }

    public function sendEmail($strBody)
    {
    
        $message = \Swift_Message::newInstance()
            ->setSubject($this->strSubject)
            ->setFrom($this->strFrom)   
            ->setTo($this->strTo);
    
        $message->setBody($strBody,'text/html');


        $mailer = $this->container->get('mailer')->send($message);
    }
}
?>