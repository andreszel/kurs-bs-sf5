<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Service\ContactMailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $entityManager, ContactMailer $contactMailer): Response
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $contact = $form->getData();

            $contactMailer->sendMessageToAdmin("Nowa wiadomość z formularza kontaktowego", $contact);
            $contactMailer->sendMessageToUser($contact->getEmail(), "Powiadomienie z Kurs BS SF5", $contact);

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
