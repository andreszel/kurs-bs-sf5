<?php

namespace App\Service;

use App\Entity\Contact;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class ContactMailer
{
    private $mailer;

    private $adminEmail;

    private $adminName;

    public function __construct(MailerInterface $mailer, string $adminEmail, string $adminName) {
        $this->mailer = $mailer;
        $this->adminEmail = $adminEmail;
        $this->adminName = $adminName;
    }

    public function sendMessageToAdmin(string $subject, Contact $contact): void
    {
        $context = [
            'name' => $contact->getName(),
            'emailAddress' => $contact->getEmail(),
            'subject' => $contact->getSubject(),
            'message' => $contact->getMessage()
        ];
        $filename = 'contact-admin';

        $this->send($this->adminEmail, $subject, $contact, $context, $filename);
    }

    public function sendMessageToUser(string $to, string $subject, Contact $contact): void
    {
        $context = [
            'name' => $contact->getName(),
            'emailAddress' => $contact->getEmail(),
        ];
        $filename = 'contact-user';

        $this->send($to, $subject, $contact, $context, $filename);
    }

    private function send(string $to, string $subject, Contact $contact, array $context, string $filename): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->adminEmail, $this->adminName))
            ->to($to)
            ->subject($subject)
            ->htmlTemplate('emails/'.$filename.'.html.twig')
            ->context($context);

        $this->mailer->send($email);
    }
}