<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Imię i nazwisko'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adres email'
            ])
            ->add('subject', null, [
                'label' => 'Temat wiadomości',
                'help' => 'Wpisz temat, którego dotyczy wiadomość'
            ])
            ->add('message', null, [
                'label' => 'Treść wiadomości',
                'help' => 'Opisz swój problem'
            ])
            ->add('send', SubmitType::class, [
                'label' => 'Wyślij'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
