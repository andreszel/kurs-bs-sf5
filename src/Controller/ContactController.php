<?php

namespace App\Controller;

use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $contact = $form->getData();

            $emailAdmin = (new TemplatedEmail())
                ->from(new Address('notify@kursbs.pl', 'BeeS'))
                ->to('szelkaandrzej@gmail.com')
                ->subject("Notify form Kurs BS")
                ->htmlTemplate('emails/contact-admin.html.twig')
                ->context([
                    'name' => $contact->getName(),
                    'emailAddress' => $contact->getEmail(),
                    'subject' => $contact->getSubject(),
                    'message' => $contact->getMessage()
                ]);

            $mailer->send($emailAdmin);

            $emailUser = (new TemplatedEmail())
                ->from(new Address('notify@kursbs.pl', 'BeeS'))
                ->to($contact->getEmail())
                ->subject("Notify form Kurs BS")
                ->htmlTemplate('emails/contact-user.html.twig')
                ->context([
                    'name' => $contact->getName(),
                    'emailAddress' => $contact->getEmail(),
                ]);
            $mailer->send($emailUser);

            $entityManager->persist($contact);
            $entityManager->flush();

            $this->addFlash('success', 'Form has benn send. Tank for your message.');

            $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
