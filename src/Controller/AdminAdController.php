<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ad", name="admin_ad")
     */
    public function index()
    {
        return $this->render('admin_ad/index.html.twig', [
            'controller_name' => 'AdminAdController',
        ]);
    }
}
