<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class AddHelper
{
    private $mailer;

    private $adminEmail;

    public function __construct(MailerInterface $mailer, string $adminEmail)
    {
        $this->mailer = $mailer;
        $this->adminEmail = $adminEmail;
    }

    public function add(int $a, int $b): int
    {
        $result = $a + $b;
        $email = (new Email())
            ->from('hello@example.com')
            ->to($this->adminEmail)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Test Symfony Mailer!')
            ->text('Result: ' . $result)
            ->html('<p>HTML integration!</p>');

        $this->mailer->send($email);

        return $result;
    }

    // UNIT TEST
    /* public function addForUnitTest(int $a, int $b): int
    {
        return $a + $b;
    } */
}