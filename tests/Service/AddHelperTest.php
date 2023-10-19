<?php

namespace App\Tests\Service;

/* use App\Service\AddHelper;
use PHPUnit\Framework\TestCase; */

use App\Service\AddHelper;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AddHelperTest extends KernelTestCase
{
    public function testAdd(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $addHelper = $container->get(AddHelper::class);

        $this->assertEquals(3, $addHelper->add(1,2));
    }
}

// UNIT TEST - jeżeli testujemy tylko metodę, bez wstrzykiwania zależności
/* class AddHelperTest extends TestCase
{
    public function testAdd(): void
    {
        $addHelper = new AddHelper();

        $this->assertEquals(3, $addHelper->addForUnitTest(2,1));
    }
}
 */