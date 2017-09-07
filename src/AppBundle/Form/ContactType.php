<?php

namespace AppBundle\Form;

use AppBundle\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sender', TextType::class, ['label' => 'contact.sender'])
            ->add('zone', ChoiceType::class, [
                'choices' => array_combine(array_keys(Contact::ZONES), array_keys(Contact::ZONES)),
                'mapped'=>false
            ])
            ->add('subject', TextType::class, ['label' => 'contact.subject', 'required' => false])
            ->add('message', TextareaType::class, ['label' => 'contact.message', 'required' => false])
        ;

        $builder->addEventListener(FormEvents::POST_SET_DATA, [$this, 'countryListListener']);
        $builder->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'countryListListener']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['partial'=>true]);
        $resolver->setNormalizer('partial', function($value) { return boolval($value); });

    }

    public function countryListListener(FormEvent $event)
    {
        $choices = $this->getCountriesByZone($event->getData());
        $event->getForm()
            ->add('country', ChoiceType::class, ['choices' => $choices, 'mapped'=>false]);
    }

    public function getCountriesByZone($formData)
    {
        if(!isset($formData['zone']) || $formData['zone'] === null) {
            return [];
        }

        return array_flip(Contact::ZONES[$formData['zone']]);
    }
}
