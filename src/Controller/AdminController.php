<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/super-admin')]
class AdminController extends AbstractController
{
    #[Route('/dashboard', name: 'app_admin_dashboard')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    
    #[Route('/comment', name: 'app_admin_comment')]
    public function comment(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_COMMENT_MODERATOR');

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    
}
