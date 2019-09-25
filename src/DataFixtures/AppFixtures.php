<?php

  namespace App\DataFixtures;

  use App\Entity\Ad;
  use App\Entity\Image;
  use Doctrine\Bundle\FixturesBundle\Fixture;
  use Doctrine\Common\Persistence\ObjectManager;
  use Faker\Factory;

  class AppFixtures extends Fixture
  {
    public function load(ObjectManager $manager)
    {

      $faker = Factory::create ( 'FR-fr' );


      for ($i = 1; $i <= 30; $i++) {
        $ad = new Ad();

        $title = $faker->sentence ( 4 );
        $coverImage = $faker->imageUrl ( 1000 , 350 );
        $introduction = $faker->paragraph ( 2 );
        $content = '<p>' . join ( '</p><p>' , $faker->paragraphs ( 3 ) ) . '</p>';

        $ad ->setTitle ( $title )
            ->setCoverImage ( $coverImage )
            ->setIntroduction ( $introduction )
            ->setContent ( $content )
            ->setPrice ( mt_rand(35, 800))
            ->setRooms ( mt_rand(1 , 6));

        for ($j = 1 , $jMax = random_int ( 3 , 5 ); $j <= $jMax; $j++) {
          $image = new Image();

          $image->setUrl ( $faker->imageUrl () )
                ->setCaption ( $faker->sentence () )
                ->setAd ( $ad );

          $manager->persist ( $image );
        }

        $manager->persist ( $ad );
      }
      $manager->flush ();
    }
  }
