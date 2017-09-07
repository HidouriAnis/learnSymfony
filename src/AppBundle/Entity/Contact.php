<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Contact
{
    const ZONES = [
        'Europe'=>['France', 'Belgique'],
        'Afrique' => ['Tunisie', 'Maroc', 'Algérie'],
        'Asie' => ['Japon', 'Chine', 'Népale']
    ];

    /**
     * @Assert\NotBlank(message="validation.contact.sender")
     * @Assert\Email()
     */
    public $sender;

    /**
     * @Assert\NotBlank(message = "validation.contact.subject")
     * @Assert\Length(min = 10, max = 50)
     */
    public $subject;

    /**
     * @Assert\NotBlank(message = "validation.contact.message")
     */
    public $message;
}
