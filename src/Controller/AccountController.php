<?php

  namespace App\Controller;

  use App\Entity\PasswordUpdate;
  use App\Entity\User;
  use App\Form\AccountType;
  use App\Form\PasswordUpdateType;
  use App\Form\RegistrationType;
  use Doctrine\Common\Persistence\ObjectManager;
  use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use Symfony\Component\Form\FormError;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\Routing\Annotation\Route;
  use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
  use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

  class AccountController extends AbstractController
  {
    /**
     * @Route("/account", name="account_index")
     * @IsGranted("ROLE_USER")
     */
    public function myAccount() {
      return $this->render ('user/index.html.twig', [
        'user' => $this->getUser ()
      ]);
    }


    /**
     * @Route("/login", name="account_login")
     *
     * @param AuthenticationUtils $utils
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
      $error = $utils->getLastAuthenticationError ();
      $username = $utils->getLastUsername ();

      return $this->render ( 'account/login.html.twig' , [
        'hasError' => $error !== null ,
        'username' => $username
      ] );
    }

    /**
     * formulaire d'inscription
     *
     * @Route("/register", name="account_register")
     * @param Request $request
     * @param ObjectManager $manager
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function register(Request $request , ObjectManager $manager , UserPasswordEncoderInterface $encoder): Response
    {
      $user = new User;
      $form = $this->createForm ( RegistrationType::class , $user );

      $form->handleRequest ( $request );

      if ($form->isSubmitted () && $form->isValid ()) {
        $hash = $encoder->encodePassword ( $user , $user->getHash () );
        $user->setHash ( $hash );
        $manager->persist ( $user );
        $manager->flush ();

        $this->addFlash ( 'success' , "Votre compte a bien été enregistré" );

        return $this->redirectToRoute ( 'account_login' );
      }

      return $this->render ( 'account/registration.html.twig' , [
        'form' => $form->createView ()
      ] );
    }


    /**
     * Edition profil utilisateur
     *
     * @Route("/account/profile", name="edit_account")
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function profile(Request $request , ObjectManager $manager): Response
    {

      $user = $this->getUser ();
      $form = $this->createForm ( AccountType::class , $user );

      $form->handleRequest ( $request );

      if ($form->isSubmitted () && $form->isValid ()) {
        $manager->persist ( $user );
        $manager->flush ();

        $this->addFlash ( 'success' , "Données modifiées avec succès" );

      }
      return $this->render ( 'account/profile.html.twig' , [
        'form' => $form->createView ()
      ] );
    }

    /**
     * Modification MDP
     *
     * @Route("/account/password-update", name="update_password")
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param ObjectManager $manager
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function updatepassword(Request $request , ObjectManager $manager , UserPasswordEncoderInterface $encoder)
    {
      $passwordUpdate = new PasswordUpdate();

      $user = $this->getUser ();

      $form = $this->createForm ( PasswordUpdateType::class , $passwordUpdate );
      $form->handleRequest ( $request );

      if ($form->isSubmitted () && $form->isValid ()) {

        //Verification MDP actuel avec celui de l'utilisateur
        if (!password_verify ( $passwordUpdate->getOldPassword () , $user->getHash () )) {

          $form->get ( 'oldPassword' )->addError ( new FormError( 'Mot de passe incorrect' ) );


        } else {
          $newPassword = $passwordUpdate->getNewPassword ();
          $hash = $encoder->encodePassword ( $user , $newPassword );

          $user->setHash ( $hash );


          $manager->persist ( $user );
          $manager->flush ();

          $this->addFlash ( 'success' , 'modification effectuée' );

          return $this->redirectToRoute ( 'homepage' );
        }
      }
      return $this->render ( 'account/password.html.twig' , [
        'form' => $form->createView ()
      ] );
    }



    /**
     * affichage du profil
     *
     * @Route("/account/show", name="account")
     *
     * @return Response
     */
    public function show()
    {
      return $this->render ( 'user/index.html.twig' );
    }

    /**
     * @Route ("/logout", name="account_logout")
     * @return void
     */
    public function logout(): void
    {

    }



  }
