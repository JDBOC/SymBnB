<?php

  namespace App\Controller;

  use App\Entity\User;
  use App\Form\AccountType;
  use App\Form\RegistrationType;
  use Doctrine\Common\Persistence\ObjectManager;
  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\Routing\Annotation\Route;
  use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
  use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

  class AccountController extends AbstractController
  {
    /**
     * @Route("/login", name="account_login")
     *
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
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
      $user = new User;
      $form = $this->createForm ( RegistrationType::class , $user );

      $form->handleRequest ($request);

      if ($form->isSubmitted () && $form->isValid ()) {
        $hash = $encoder->encodePassword ($user, $user->getHash ());
        $user->setHash ($hash);
        $manager->persist ($user);
        $manager->flush ();

        $this->addFlash ('success',"Votre compte a bien été enregistré");

        return $this->redirectToRoute ('account_login');
      }

      return $this->render ( 'account/registration.html.twig' , [
        'form' => $form->createView ()
      ] );
    }

    /**
     * Edition profil utilisateur
     *
     * @Route("/account/profile", name="edit_account")
     *
     * @param Request $request
     * @return Response
     */
    public function profile(Request $request, ObjectManager $manager): Response
    {

      $user = $this->getUser ();
      $form = $this->createForm (AccountType::class, $user);

      $form ->handleRequest ($request);

      if ($form -> isSubmitted () && $form -> isValid ()) {
        $manager->persist ( $user );
        $manager->flush ();

        $this->addFlash ('success', "Données modifiées avec succès");

      }
    return $this->render('account/profile.html.twig', [
      'form' => $form->createView ()
    ]);
    }

    /**
     * Modification MDP
     *
     * @Route("/account/password-update", name="update_password")
     *
     * @return Response
     */
    public function updatepassword() {
    return $this->render ('account/password.html.twig');
    }

    /**
     * @Route ("/logout", name="account_logout")
     * @return void
     */
    public function logout()
    {

    }
  }