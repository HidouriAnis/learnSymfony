<?php

/*
 * services:
    # ...

    app.decorating_mailer:
      class:     AppBundle\DecoratingMailer
      decorates: app.mailer
      arguments: ['@app.decorating_mailer.inner']
      public:    false
 */
/*

class DecoratingMailer implements MailerInterface
{

    public function __construct(Mailer $mailer, LoggerInterface $logger) {
        $this->mailer = $mailer;
    }
    public function send($to, $content){
        $content .= 'Sended by Sami.';
        $this->mailer->send($to, $content);
        $this->logger->info('New mail sent by Sami.');
    }
}
}

*/