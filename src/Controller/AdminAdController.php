<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ad", name="admin_ad_index")
     */
    public function index(AdRepository $repository)
    {
        return $this->render('admin/ad/index.html.twig', [
            'ad' => $repository->findAll ()
        ]);
    }

  /**
   * Permet d'afficher le formulaire d'édition
   *
   * @Route("/admin/ad/{id}/edit", name="admin_ad_edit")
   *
   * @param Ad $ad
   * @param Request $request
   * @param ObjectManager $manager
   * @return Response
   */
    public function edit(Ad $ad, Request $request, ObjectManager $manager) {
      $form = $this->createForm (AdType::class, $ad);
      $form->handleRequest ($request);

      if ($form->isSubmitted () && $form->isValid ()) {
        $manager->persist ($ad);
        $manager->flush ();

        $this->addFlash ('success', "Modification effectuée");
      }

      return $this->render ('admin/ad/edit.html.twig', [
        'ad' => $ad,
        'form' => $form->createView ()
      ]);
    }


}
