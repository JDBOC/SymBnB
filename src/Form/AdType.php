<?php

namespace App\Form;


use App\Form\ImageType;
use App\Entity\Ad;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class AdType extends AbstractType
{

  /**
   * Permet de configurer chaque champs du formulaire
   *
   * @param $label
   * @param $placeholder
   * param array $options
   * @return array
   */
    private function getConfig($label, $placeholder, $options = []){
      return array_merge ([
        'label' => $label,
        'attr' => [
          'placeholder' => $placeholder
      ]
            ], $options);
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,
              $this->getConfig ("Titre", "indiquez le titre de l'annonce")
              )
            ->add('slug',
              TextType::class,
                   $this->getConfig ("adresse Web", "non essentiel", [
                     'required' => false
                   ])
                  )
            ->add('price', MoneyType::class,
              $this->getConfig ("Prix par nuit", "Indiquez un prix")
                  )
            ->add('introduction', TextType::class,
              $this->getConfig ("Introduction", "Introduction")
                  )
            ->add('content', CKEditorType::class,
              $this->getConfig ("Description", "Description du bien")
                  )
            ->add('coverImage', UrlType::class,
              $this->getConfig ("URL de l'image", "indiquez l'image principale")
                  )
            ->add('rooms', IntegerType::class,
              $this->getConfig ("nombre de chambres", "indiquez le nombre de chambres disponibles")
                  )

            ->add('Images',
            CollectionType::class,
              [
                'entry_type' => ImageType::class,
                'allow_add' => true,
                'allow_delete' => true
              ]

            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
          [
            'entry_type' => ImageType::class
          ]
        ]);
    }
}
