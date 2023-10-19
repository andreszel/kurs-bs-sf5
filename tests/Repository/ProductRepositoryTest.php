<?php

namespace App\Tests\Repository;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductRepositoryTest extends KernelTestCase
{
    public function testAddProduct(): void
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();

        $entityManager = $container->get(EntityManagerInterface::class);

        //$category = $entityManager->getRepository(Category::class)->findRandomCategory();
        $category = new Category();
        $category->setName('Koszulki');

        $product = new Product();
        $product->setName('Koszulka')
            ->setDescription('Opis produktu - koszulka')
            ->setPrice(200,43)
            ->setActive(true)
            ->setEan('321432432432')
            ->setCategory($category);

        $entityManager->persist($category);
        $entityManager->persist($product);
        $entityManager->flush();

        //$this->assertSame('test', $kernel->getEnvironment());
        // $routerService = static::getContainer()->get('router');
        // $myCustomService = static::getContainer()->get(CustomService::class);

        $productEntity = $entityManager->getRepository(Product::class)->findOneBy(['name' => 'Koszulka']);

        $this->assertInstanceOf(Product::class, $productEntity);
        $this->assertIsInt($productEntity->getId());
    }
}
