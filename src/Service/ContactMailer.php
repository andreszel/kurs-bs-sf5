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
        $email = (new TemplatedEmail())
            ->from(new Address($this->adminEmail, $this->adminName))
            ->to($this->adminEmail)
            ->subject($subject)
            ->htmlTemplate('emails/contact-admin.html.twig')
            ->context([
                'name' => $contact->getName(),
                'emailAddress' => $contact->getEmail(),
                'subject' => $contact->getSubject(),
                'message' => $contact->getMessage()
            ]);

        $this->mailer->send($email);
    }

    public function sendMessageToUser(string $to, string $subject, Contact $contact): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->adminEmail, $this->adminName))
            ->to($to)
            ->subject($subject)
            ->htmlTemplate('emails/contact-user.html.twig')
            ->context([
                'name' => $contact->getName(),
                'emailAddress' => $contact->getEmail(),
            ]);
        $this->mailer->send($email);
    }
}