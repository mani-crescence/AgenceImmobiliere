<?php

namespace App\Controller;

use App\Repository\PropertyRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;


class HomeController
{

    private Environment $twig;

    public function __construct(Environment $twig)
    {
      $this->twig = $twig;
    }

    /**
      * @Route("/", name="home")
    */
    public function index(PropertyRepository $repository): Response
    {
      $properties = $repository->findLatest();
      return new Response($this->twig->render('pages/page/home.html.twig' , ['properties' => $properties]
      )) ;
    }
}