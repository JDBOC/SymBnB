<?php

  namespace App\Controller;


  use App\Repository\AdRepository;
  use App\Repository\UserRepository;
  use Symfony\Bundle\FrameworkBundle\Controller\Controller;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\Routing\Annotation\Route;


  class HomeController extends Controller {

    /**
     * @Route("/", name="homepage")
     *
     * @return Response
     */
    public function home(AdRepository $adRepository, UserRepository $userRepository) {
      return $this->render (
        'home.html.twig',
        [
          'ad' => $adRepository->findBestAds (3),
          'users' => $userRepository->findBestUsers ()
        ]
      );
    }
  }