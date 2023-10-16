<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product')]
class ProductController extends AbstractController
{
    private $entityManager;
    private $productRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ProductRepository $productRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->productRepository = $productRepository;
    }

    #[Route('/', name: 'app_admin_product')]
    public function index(): Response
    {
        /* $routeName = $request->attributes->get('_route');
        $routeParameters = $request->attributes->get('_route_params');
        // use this to get all the available attributes (not only routing ones):
        $allAttributes = $request->attributes->all();

        dump($routeName);
        dump($routeParameters);
        dump($allAttributes); */

        $products = $this->productRepository->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/new', name: 'app_admin_product_new')]
    public function new(Request $request): Response
    {
        $form = $this->createForm(ProductType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $product = $form->getData();

            $this->entityManager->persist($product);
            $this->entityManager->flush();

            $this->addFlash('success', 'Produkt został dodany');

            return $this->redirectToRoute('app_admin_product');
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/edit/{id}', name: 'app_admin_product_edit')]
    public function edit(Product $product, Request $request): Response
    {
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $product = $form->getData();

            $this->entityManager->persist($product);
            $this->entityManager->flush();

            $this->addFlash('success', 'Produkt został zaktualizowany');

            return $this->redirectToRoute('app_admin_product');
        }

        return $this->render('product/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/show/{id}', name: 'app_admin_product_show', requirements: ['id' => '\d+'])]
    public function show(Request $request, int $id): Response
    {
        $product = $this->productRepository->find($id);

        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/delete/{id<\d+>}', name: 'app_admin_product_delete')]
    public function delete(Request $request, int $id): Response
    {
        $product = $this->productRepository->findOneBy(['id' => $id]);

        if($product) {
            $this->entityManager->remove($product);
            $this->entityManager->flush();
        }


        return $this->redirectToRoute('app_admin_product');
    }

    #[Route('/sales', name: 'app_admin_product_sales')]
    public function sales(Request $request): Response
    {
        $products = $this->productRepository->findAllLowerThanPrice(100);

        return $this->render('product/sales.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/greater-qb', name: 'app_admin_product_greater_qb')]
    public function greaterQb(Request $request): Response
    {
        $products = $this->productRepository->findAllGreaterThanPrice(100, true);

        return $this->render('product/greater_qb.html.twig', [
            'products' => $products,
        ]);
    }

}
