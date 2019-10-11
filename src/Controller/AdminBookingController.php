<?php

  namespace App\Controller;

  use App\Entity\Booking;
  use App\Form\AdminBookingType;
  use App\Repository\BookingRepository;
  use App\Service\PaginationService;
  use Doctrine\Common\Persistence\ObjectManager;
  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\Routing\Annotation\Route;

  class AdminBookingController extends AbstractController
  {
    /**
     * @Route("/admin/booking/{page<\d+>?1}", name="admin_booking_index")
     *
     * @param BookingRepository $repository
     * @param int $page
     * @param PaginationService $pagination
     * @return Response
     */
    public function index(BookingRepository $repository , $page , PaginationService $pagination)
    {
      $pagination ->setEntityClass ( Booking::class )
                  ->setCurrentPage ( $page );

      return $this->render ( 'admin/booking/index.html.twig' , [
        'pagination' => $pagination
      ] );
    }

    /**
     * Permet à l'admin de pouvoir éditer une réservation
     *
     * @Route("/admin/booking/{id}/edit", name="admin_booking_edit")
     *
     * @param Booking $booking
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function edit(Booking $booking , Request $request , ObjectManager $manager)
    {
      $form = $this->createForm ( AdminBookingType::class , $booking , [
        'validation_groups' => ["Default"]
      ] );
      $form->handleRequest ( $request );

      if ($form->isSubmitted () && $form->isValid ()) {
        $booking->setAmount ( 0 );
        $manager->persist ( $booking );
        $manager->flush ();

        $this->addFlash ( 'success' , "modification effectuée sur la réservation N° {$booking->getId ()}" );

        return $this->redirectToRoute ( 'admin_booking_index' );
      }

      return $this->render ( 'admin/booking/edit.html.twig' , [
          'booking' => $booking ,
          'form' => $form->createView ()
        ]
      );
    }

    /**
     * Permet à l'administrateur de supprimer une réservation
     *
     * @Route("admin/booking/{id}/delete", name="admin_booking_delete")
     * @param Booking $booking
     * @param ObjectManager $manager
     *
     * @return Response
     */
    public function delete(Booking $booking , ObjectManager $manager)
    {
      $manager->remove ( $booking );
      $manager->flush ();

      $this->addFlash ( 'success' , "la réservation {$booking->getId ()} a été supprimée" );

      return $this->redirectToRoute ( 'admin_booking_index' );
    }


  }
