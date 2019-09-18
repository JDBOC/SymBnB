<?php

  namespace App\Controller;



  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use Symfony\Component\Routing\Annotation\Route;
  use Symfony\Component\HttpFoundation\Response;

  class HomeController extends AbstractController {

    /**
     * @Route("/hello/{prenom}/age/{age}", name="hello")
     * @Route("/salut", name="hello_base")
     * @Route("/hello/{prenom}", name="hello_prenom")
     */
    public function hello($prenom = "anon", $age = 0){
      return $this->render (
        'hello.html.twig',
        [
          'prenom' => $prenom,
          'age' => $age
        ]
      );
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