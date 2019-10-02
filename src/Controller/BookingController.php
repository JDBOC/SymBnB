<?php

  namespace App\Controller;

  use App\Entity\Ad;
  use App\Entity\Booking;
  use App\Form\BookingType;
  use Doctrine\Common\Persistence\ObjectManager;
  use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\Routing\Annotation\Route;

  class BookingController extends AbstractController
  {
    /**
     * @Route("/ad/{slug}/book", name="booking_create")
     * @IsGranted("ROLE_USER")
     * @param Ad $ad
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function book(Ad $ad , Request $request , ObjectManager $manager)
    {
      $booking = new Booking();
      $form = $this->createForm ( BookingType::class , $booking );
      $form->handleRequest ( $request );

      if ($form->isSubmitted () && $form->isValid ()) {
        $user = $this->getUser ();

        $booking->setBooker ( $user )
                ->setAd ( $ad );

        //Si les dates ne sont pas disponibles, message d'erreur
        if (!$booking->isBookableDates ()) {
          $this->addFlash ( 'warning' , "Ces dates ne sont pas disponibles" );
        } else {
          // sinon enregistrement et redirection
          $manager->persist ( $booking );
          $manager->flush ();
          return $this->redirectToRoute ( 'booking_show' , [
            'id' => $booking->getId () ,
            'withAlert' => true
          ] );
        }
      }

      return $this->render ( 'booking/book.html.twig' , [
        'ad' => $ad ,
        'form' => $form->createView ()
      ] );
    }

    /**
     * affiche la page d'une réservation
     *
     * @Route("/booking/{id}", name="booking_show")
     *
     * @param Booking $booking
     * @return Response
     */
    public function show(Booking $booking)
    {
      return $this->render ( 'booking/show.html.twig' , [
        'booking' => $booking
      ] );
    }


  }
