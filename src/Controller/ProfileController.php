<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_admin_profile')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    #[Route('/profile/edit', name: 'app_admin_profile_edit')]
    public function edit(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('profile/edit.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }
}
