<?php

namespace AppBundle\Service;

use AppBundle\Entity\Contact;
use Psr\Log\LoggerInterface;

class ContactService
{
    private $recipient;
    private $mailer;
    /** @var  LoggerInterface */
    private $logger;

    public function __construct($recipient, \Swift_Mailer $mailer)
    {
        $this->recipient = $recipient;
        $this->mailer = $mailer;
    }

    public function setLogger(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    public function sendMail(Contact $contact)
    {
        $message = \Swift_Message::newInstance()
            ->setTo($this->recipient)
            ->setFrom($contact->sender)
            ->setSubject($contact->subject)
            ->setBody($contact->message)
        ;

        if($this->logger !== null){
            $this->logger->info('New contact from app.');
        }

        $this->mailer->send($message);
    }
}
