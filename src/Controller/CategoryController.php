<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/category')]
class CategoryController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private CategoryRepository $categoryRepository) {
    }

    #[Route('/', name: 'app_admin_category')]
    public function index(): Response
    {
        $categories = $this->categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/new', name: 'app_admin_category_new')]
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $form = $this->createForm(CategoryType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $category = $form->getData();

            $this->entityManager->persist($category);
            $this->entityManager->flush();

            $this->addFlash('success', 'Kategoria została dodana poprawnie');

            return $this->redirectToRoute('app_admin_category');
        }

        return $this->render('category/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/edit/{id}', name: 'app_admin_category_edit')]
    public function edit(Category $category, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $this->entityManager->persist($category);
            $this->entityManager->flush();

            $this->addFlash('success', 'Kategoria została zaktualizowana');

            return $this->redirectToRoute('app_admin_category');
        }

        return $this->render('category/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
