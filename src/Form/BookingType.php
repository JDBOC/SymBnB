<?php

  namespace App\Form;

  use App\Entity\Booking;


  use App\Form\DataTransformer\FrenchToDateTimeTransformer;
  use DateTime;
  use Symfony\Component\Form\Extension\Core\Type\TextareaType;
  use Symfony\Component\Form\Extension\Core\Type\TextType;
  use Symfony\Component\Form\FormBuilderInterface;
  use Symfony\Component\Form\FormTypeInterface;
  use Symfony\Component\OptionsResolver\OptionsResolver;


  class BookingType extends ApplicationType
  {
    public function __construct(FrenchToDateTimeTransformer $transformer)
    {
      $this->transformer = $transformer;
    }


    public function buildForm(FormBuilderInterface $builder , array $options)
    {
      $builder
        ->add ( 'startDate' , TextType::class , $this->getConfig ( "Date d'arrivée" , "Votre date d'arrivée") )
        ->add ( 'endDate' , TextType::class , $this->getConfig ( "date de départ" , "Votre date de départ") )
        ->add ( 'comment' , TextareaType::class , $this->getConfig ( "Commentaire" , "laissez nous un commentaire" , [
          "required" => false
        ] ) );

      $builder -> get ('startDate')->addModelTransformer ($this->transformer);
      $builder ->get ('endDate')->addModelTransformer ($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
      $resolver->setDefaults ( [
        'data_class' => Booking::class ,
      ] );
    }
  }
