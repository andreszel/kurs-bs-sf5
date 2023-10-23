<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginFormTest extends WebTestCase
{
    public function testLoginUserFormLogin(): void
    {
        $email = 'webtestcase@kursbs.pl';
        $password = 'test1234';
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/'); // Get homepage

        $this->assertResponseIsSuccessful();

        $loginLink = $crawler->selectLink('Login')->link();
        $crawler = $client->click($loginLink);

        //$csrfToken = static::getContainer()->get('security.csrf.token_manager')->getToken('division_item');

        // select the button
        $authCrawlerNode = $crawler->selectButton('Zaloguj się'); // Get form button.
        $form = $authCrawlerNode->form();

        /* $form['email'] = $email;
        $form['password'] = $password; */
        //$form['_remember_me']->tick();

        $form = $authCrawlerNode->form(array(
            'email' => $email,
            'password' => $password
        ));

        $crawler = $client->submit($form);

        /* $crawler = $client->submit($form, [
            'email' => $email,
            'password' => $password
        ]); */

        /* $client->submitForm($authCrawlerNode, [
            'email' => $email,
            'password' => $password,
            //'_csrf_token' => $csrfToken
        ], 'POST'); */

        $link = $crawler->selectLink('Admin')->link();
        $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //$this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Dashboard admin');
    }

    public function test_user_can_view_a_login_form()
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200, $response->getStatusCode());
    }

    /* public function test_user_cannot_view_a_login_form_when_authenticated()
    {
        $user = factory(User::class)->make();

        $response = $this->actingAs($user)->get('/login');

        $response->assertRedirect('/home');
    }

    public function test_user_can_login_with_correct_credentials()
    {
        // Creamos un factory de user para que lo utilice en la prueba
        $user = factory(User::class)->create([
            'password' => bcrypt($password = '123456'),
        ]);


        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertRedirect('/home');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_incorrect_password()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt('123456'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'invalid-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }


    public function test_remember_me_functionality()
    {
        $user = factory(User::class)->create([
            'id' => random_int(1, 100),
            'password' => bcrypt($password = '123456'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
            'remember' => 'on',
        ]);

        $response->assertRedirect('/home');
        // cookie assertion goes here
        $this->assertAuthenticatedAs($user);
    } */
}
