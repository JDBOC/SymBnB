<?php

  namespace App\Service;

  use Doctrine\Common\Persistence\ObjectManager;

  class StatsService {

    private $manager;

    public function __construct(ObjectManager $manager)
    {
      $this->manager = $manager;
    }

    public function getUsersCount() {
      return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')-> getSingleScalarResult();
    }

    public function getAdCount() {
      return $this->manager->createQuery('SELECT COUNT(a) FROM App\Entity\Ad a')-> getSingleScalarResult();
    }

    public function getBookingsCount() {
      return $this->manager->createQuery('SELECT COUNT(b) FROM App\Entity\Booking b')->getSingleScalarResult();
    }

    public function getCommentsCount() {
      return $this->manager->createQuery('SELECT COUNT(c) FROM App\Entity\Comment c')-> getSingleScalarResult();
    }

    public function getStats()
    {
      $users = $this->getUsersCount ();
      $ad = $this->getAdCount ();
      $bookings = $this->getBookingsCount ();
      $comments = $this->getCommentsCount ();

      return compact ('users', 'ad', 'bookings', 'comments');
    }

    public function getAdStats($direction)
    {
      return $this->manager->createQuery(
        'SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture
         FROM App\Entity\Comment c 
         JOIN c.ad a
         JOIN a.author u
         GROUP BY a
         ORDER BY note ' . $direction
      )
        ->setMaxResults(5)
        ->getResult();
    }
  }