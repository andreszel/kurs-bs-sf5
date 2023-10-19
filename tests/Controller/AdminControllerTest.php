<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testLoginUserToAdminDashboard(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail('webtestcase@kursbs.pl');

        $client->loginUser($testUser);

        $client->request('GET', '/super-admin/dashboard');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Dashboard admin');

        /* $history = $client->getHistory();
        $cookieJar = $client->getCookieJar(); */
    }

    public function testClickLinkToAdminDashboard(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail('webtestcase@kursbs.pl');

        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/');
        $link = $crawler->selectLink('Admin')->link();

        $client->click($link);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Dashboard admin');
    }
}
