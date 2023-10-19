<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; $i++)
        {
            $product = new Product();
            $product->setName('Product ' . $i)
                ->setDescription('Opis produktu - koszulka ' . $i)
                ->setPrice(mt_rand(10,100))
                ->setEan(mt_rand(1000000,3000000))
                ->setActive(false);

            $manager->persist($product);
        }

        $manager->flush();
    }
}
