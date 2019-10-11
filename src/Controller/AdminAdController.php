<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;

use App\Service\PaginationService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminAdController extends AbstractController
{
  /**
   * @Route("/admin/ad/{page<\d+>?1}", name="admin_ad_index")
   * @param AdRepository $repository
   * @return Response
   */
    public function index(AdRepository $repository, $page, PaginationService $pagination)
    {
     $pagination -> setEntityClass (Ad::class)
                  ->setCurrentPage ($page);


       return $this->render('admin/ad/index.html.twig', [
            'pagination' => $pagination
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

  /**
   * Permet la suppression d'une annonce par user admin
   *
   * @Route("/admin/ad/{id}/delete", name="admin_ad_delete")
   * @param Ad $ad
   * @param ObjectManager $manager
   * @return Response
   */
    public function delete(Ad $ad, ObjectManager $manager) {
      if (count($ad->getBookings ())>0) {
        $this->addFlash ('warning', "Vous ne pouvez pas supprimer l'annionce {$ad->getTitle ()} car elle possède des réservations");
      } else {
        $manager->remove ( $ad );
        $manager->flush ();

        $this->addFlash ( 'success' , "L'annonce {$ad->getTitle ()} a bien été supprimée" );
      }
      return $this->redirectToRoute ('admin_ad_index');
    }


}
