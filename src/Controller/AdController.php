<?php

  namespace App\Controller;

  use App\Entity\Ad;
  use App\Entity\Image;
  use App\Entity\User;
  use App\Form\AdType;
  use App\Repository\AdRepository;
  use Doctrine\Common\Persistence\ObjectManager;
  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\Routing\Annotation\Route;

  class AdController extends AbstractController
  {
    /**
     * @Route("/ad", name="ad")
     * @param AdRepository $repo
     * @return Response
     */
    public function index(AdRepository $repo)
    {
      $ads = $repo->findAll ();

      return $this->render ( 'ad/index.html.twig' , [
        'ads' => $ads ,
      ] );
    }

    /**
     * création d'une annonce
     *
     * *@Route("ad/new", name="ad_create")
     *
     * @return Response
     */
    public function create(Request $request , ObjectManager $manager)
    {
      $ad = new Ad();


      $form = $this->createForm ( AdType::class , $ad );
      $form->handleRequest ( $request );

      if ($form->isSubmitted () && $form->IsValid ()) {

        foreach ($ad->getImages () as  $image){
          $image->setAd ($ad);
          $manager->persist($image);

        }

        $ad->setAuthor ($this->getUser ());

        $manager->persist ( $ad );
        $manager->flush ();

        $this->addFlash (
          'success', "l'annonce {$ad->getTitle ()} a bien été enregistrée"
        );

        return $this->redirectToRoute ( 'ad_show' , [
          'slug' => $ad->getSlug ()
        ] );
      }

      return $this->render ( 'ad/new.html.twig' , [
        'form' => $form->createView ()
      ] );
    }

    /**
     * formulaire d'édition
     *
     * @Route("/ad/{slug}/edit", name="ad_edit")
     *
     * @return Response
     *
     */
    public function edit(Request $request, Ad $ad, ObjectManager $manager){
        $form = $this->createForm (AdType::class, $ad);
        $form->handleRequest ($request);

      if ($form->isSubmitted () && $form->IsValid ()) {

        foreach ($ad->getImages () as  $image){
          $image->setAd ($ad);
          $manager->persist($image);

        }

        $manager->persist ( $ad );
        $manager->flush ();

        $this->addFlash (
          'success', "l'annonce {$ad->getTitle ()} a bien été modifiée"
        );

        return $this->redirectToRoute ( 'ad_show' , [
          'slug' => $ad->getSlug ()
        ] );
      }

        return $this->render ('ad/edit.html.twig', [
          'form' => $form->createView (),
          'ad' => $ad
        ]);

    }

    /**
     * permet d'afficher une seule annonce
     * @Route("/ad/{slug}", name="ad_show")
     * @param Ad $ad
     * @return Response
     */
    public function show(Ad $ad)
    {
      return $this->render ( 'ad/show.html.twig' , [
        'ad' => $ad
      ] );
    }


  }
