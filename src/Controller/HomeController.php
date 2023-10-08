<?php

namespace App\Controller;

use App\Service\AddHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
       //return new Response('<h1>Home controller</h1>');

       return $this->render('home/index.html.twig');
    }

    public function json_index(): Response
    {
       return new JsonResponse('Data json');
    }

    /**
     * @Route("/annotations", name="route_annotation")
     */
    public function routeWithAnnotations(): Response
    {
        return new Response('Content with annotations');
    }

    /**
     * @Route("/annotation-param/{firstName}", name="route_annotation_param")
     */
    public function routeWithAnnotationAndParam(string $firstName): Response
    {
        return new Response('Content with annotation and param. First name is: ' . $firstName);
    }

    /**
     * @Route("/twig-view", name="route_annotation_twig")
     */
    public function routeTwigView(AddHelper $addHelper): Response
    {
        $sum = $addHelper->add(rand(20,40),12);

        return $this->render('home/route_twig_view.html.twig',[
            'sum' => $sum
        ]);
    }

    /**
     * @Route("/second-twig-view", name="route_annotation_twig_second")
     */
    public function secondTwigView(): Response
    {
        return $this->render('home/route_twig_view_second.html.twig',[
            'test' => 1
        ]);
    }

    /**
     * @Route("/random", name="random_number")
     */
    public function randomNumber(): Response
    {
        $randomNumber = rand(5,60);

        return $this->render('home/random_number.html.twig',[
            'randomNumber' => $randomNumber
        ]);
    }
}