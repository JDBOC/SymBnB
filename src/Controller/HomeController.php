<?php

  namespace App\Controller;



  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use Symfony\Component\Routing\Annotation\Route;
  use Symfony\Component\HttpFoundation\Response;

  class HomeController extends AbstractController {

    /**
     * @Route("/hello", name="hello")
     */
    public function hello(){
      return new Response("coucou toi");
    }

    /**
     * @Route("/", name="homepage")
     */
    public function home() {
      $prenoms = ["JD" => 41, "Greg" => 32, "Axel" => 39, "Thomas" => 28];

      return $this->render(
        'home.html.twig', [
          'title' => "Hello World",
          'age' => 19,
          'tableau' => $prenoms
        ]
      );



    }


  }