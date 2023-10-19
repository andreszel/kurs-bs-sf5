<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginFormTest extends WebTestCase
{
    public function testLoginUserFormLogin(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $link = $crawler->selectLink('Login')->link();
        $client->click($link);

        $csrfToken = static::getContainer()->get('security.csrf.token_manager')->getToken('division_item');

        // select the button
        $buttonCrawlerNode = $crawler->selectButton('submit');
        $form = $buttonCrawlerNode->form();

        $form['email'] = 'webtestcase@kursbs.pl';
        $form['password'] = 'test1234';
        $form['_remember_me']->tick();
        $form['_csrf_token'] = $csrfToken;

        $crawler = $client->submit($form);

        $client->followRedirects(true);

        /* $crawler = $client->submit($form, [
            'email' => 'webtestcase@kursbs.pl',
            'password' => 'test1234'
        ]); */

        /* $crawler = $client->submitForm('Sign in', [
            'email' => 'webtestcase@kursbs.pl',
            'password' => 'test1234'
        ], 'POST'); */

        $link = $crawler->selectLink('Admin')->link();
        $client->click($link);


        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Dashboard admin');
    }
}
