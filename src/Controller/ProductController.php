<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product')]
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

    #[Route('/', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig');
    }

    #[Route('/add', name: 'app_product_create')]
    public function createProduct(): Response
    {
        $product = new Product();
        $category = new Category();

        $suffixCategory = rand(1,1000);
        $category->setName('T-shirt ' . $suffixCategory);

        $starPrice = rand(30,90);
        $price = $starPrice + 90.99;

        $product->setName('Koszulka')
            ->setDescription('Biała z siateczką')
            ->setPrice($price)
            ->setActive(true)
            ->setEan('12343212233');
        
        $product->setCategory($category);
        
        $this->entityManager->persist($category);
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_product');
    }

    #[Route('/list', name: 'app_product_list')]
    public function list(Request $request): Response
    {
        $products = $this->productRepository->findAll();

        $routeName = $request->attributes->get('_route');
        $routeParameters = $request->attributes->get('_route_params');
        // use this to get all the available attributes (not only routing ones):
        $allAttributes = $request->attributes->all();

        dump($routeName);
        dump($routeParameters);
        dump($allAttributes);

        return $this->render('product/list.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/show/{id}', name: 'app_product_show', requirements: ['id' => '\d+'])]
    public function show(Request $request, int $id): Response
    {
        $product = $this->productRepository->find($id);

        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/delete/{id<\d+>}', name: 'app_product_delete')]
    public function delete(Request $request, int $id): Response
    {
        $product = $this->productRepository->find($id);

        $this->entityManager->remove($product);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_product_list');
    }

    #[Route('/sales', name: 'app_product_sales')]
    public function sales(Request $request): Response
    {
        $products = $this->productRepository->findAllLowerThanPrice(100);

        return $this->render('product/sales.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/greater-qb', name: 'app_product_greater_qb')]
    public function greaterQb(Request $request): Response
    {
        $products = $this->productRepository->findAllGreaterThanPrice(100, true);

        return $this->render('product/greater_qb.html.twig', [
            'products' => $products,
        ]);
    }

}
