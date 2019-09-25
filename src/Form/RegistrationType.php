<?php

  namespace App\Form;

  use App\Entity\User;
  use FOS\CKEditorBundle\Form\Type\CKEditorType;
  use Symfony\Component\Form\Extension\Core\Type\EmailType;
  use Symfony\Component\Form\Extension\Core\Type\PasswordType;
  use Symfony\Component\Form\Extension\Core\Type\TextType;
  use Symfony\Component\Form\Extension\Core\Type\UrlType;
  use Symfony\Component\Form\FormBuilderInterface;
  use Symfony\Component\OptionsResolver\OptionsResolver;

  class RegistrationType extends ApplicationType
  {


    public function buildForm(FormBuilderInterface $builder , array $options)
    {
      $builder
        ->add ( 'firstName' , TextType::class , $this->getConfig ( "prénom" , "votre prénom" ) )
        ->add ( 'lastName' , TextType::class , $this->getConfig ( "nom" , "votre nom" ) )
        ->add ( 'email' , EmailType::class , $this->getConfig ( "email" , "votre email" ) )
        ->add ( 'picture' , UrlType::class , $this->getConfig ( "photo" , "Avatar" ) )
        ->add ( 'hash' , PasswordType::class , $this->getConfig ( "mot de passe" , "votre mot de passe" ) )
        ->add ('passwordConfirm', PasswordType::class, $this->getConfig ("confirmation du mot de passe", "confirmez votre mot de passe"))
        ->add ( 'introduction' , TextType::class , $this->getConfig ( "introduction" , "présentez vous" ) )
        ->add ( 'description' , CKEditorType::class , $this->getConfig ( "description" , "votre description" ) );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
      $resolver->setDefaults ( [
        'data_class' => User::class ,
      ] );
    }
  }
