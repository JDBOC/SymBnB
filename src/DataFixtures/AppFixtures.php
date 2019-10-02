<?php

  namespace App\DataFixtures;

  use App\Entity\Ad;
  use App\Entity\Booking;
  use App\Entity\Image;
  use App\Entity\Role;
  use App\Entity\User;
  use Doctrine\Bundle\FixturesBundle\Fixture;
  use Doctrine\Common\Persistence\ObjectManager;
  use Faker\Factory;
  use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

  class AppFixtures extends Fixture
  {
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
      $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {

      $faker = Factory::create ( 'FR-fr' );

      $adminRole = new Role();
      $adminRole->setTitle ( 'ROLE_ADMIN' );
      $manager->persist ( $adminRole );

      $adminUser = new User();
      $adminUser->setFirstName ( 'Jean Daniel' )
        ->setLastName ( 'Boccara' )
        ->setEmail ( 'jdboc@live.fr' )
        ->setHash ( $this->encoder->encodePassword ( $adminUser , 'password' ) )
        ->setPicture ( 'https://randomuser.me/api/portraits/lego/1.jpg' )
        ->setIntroduction ( $faker->sentence () )
        ->setDescription ( '<p>' . join ( '</p><p>' , $faker->paragraphs ( 5 ) ) . '</p>' )
        ->addUserRole ( $adminRole );
      $manager->persist ( $adminUser );

//gestion des utilisateurs

      $users = [];
      $genres = ['male' , 'female'];


      for ($i = 1; $i <= 10; $i++) {
        $user = new User();

        $genre = $faker->randomElement ( $genres );

        $picture = 'https://randomuser.me/api/portraits/';
        $pictureId = $faker->numberBetween ( 1 , 99 ) . '.jpg';

        $picture .= ($genre === 'male' ? 'men/' : 'women/') . $pictureId;

        $hash = $this->encoder->encodePassword ( $user , 'password' );

        $user->setFirstName ( $faker->firstName ( $genre ) )
          ->setLastName ( $faker->lastName )
          ->setEmail ( $faker->email )
          ->setIntroduction ( $faker->sentence () )
          ->setDescription ( '<p>' . join ( '</p><p>' , $faker->paragraphs ( 5 ) ) . '</p>' )
          ->setPicture ( $picture )
          ->setHash ( $hash );

        $manager->persist ( $user );
        $users[] = $user;
      }


// gestion des annonces
      for ($i = 1; $i <= 30; $i++) {
        $ad = new Ad();

        $title = $faker->sentence ( 4 );
        $coverImage = $faker->imageUrl ( 1000 , 350 );
        $introduction = $faker->paragraph ( 2 );
        $content = '<p>' . join ( '</p><p>' , $faker->paragraphs ( 3 ) ) . '</p>';

        $user = $users[mt_rand ( 0 , count ( $users ) - 1 )];

        $ad->setTitle ( $title )
          ->setCoverImage ( $coverImage )
          ->setIntroduction ( $introduction )
          ->setContent ( $content )
          ->setPrice ( mt_rand ( 35 , 135 ) )
          ->setRooms ( mt_rand ( 1 , 6 ) )
          ->setAuthor ( $user );

        for ($j = 1 , $jMax = random_int ( 3 , 5 ); $j <= $jMax; $j++) {
          $image = new Image();

          $image->setUrl ( $faker->imageUrl () )
            ->setCaption ( $faker->sentence () )
            ->setAd ( $ad );

          $manager->persist ( $image );
        }

        //gestion des Réservations
        for ($j = 1 , $jMax = random_int ( 1 , 10 ); $j <= $jMax; $j++) {
          $booking = new Booking();
          $createdAt = $faker->dateTimeBetween ( '-6 months' );
          $startDate = $faker->dateTimeBetween ( '-3 months' );

          $duration = random_int ( 3 , 15 );
          $endDate = (clone $startDate)->modify ( "+$duration days" );

          $amount = $ad->getPrice () * $duration;

          $booker = $users[mt_rand ( 0 , count ( $users ) - 1 )];
          $comment = $faker->paragraph;


          $booking->setBooker ( $booker )
                  ->setAd ( $ad )
                  ->setStartDate ( $startDate )
                  ->setEndDate ( $endDate )
                  ->setCreatedAt ( $createdAt )
                  ->setAmount ( $amount )
                  ->setComment ($comment);

          $manager->persist ($booking);

        }


        $manager->persist ( $ad );
      }
      $manager->flush ();
    }


  }


