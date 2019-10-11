<?php

  namespace App\Service;

  use Doctrine\Common\Persistence\ObjectManager;

  class PaginationService
  {
    private $entityClass;
    private $limit = 10;
    private $currentPage = 1;
    private $manager;

    public function __construct(ObjectManager $manager)
    {
      $this->manager = $manager;
    }

    public function getPages() {
      // connaitre total des entrées
      $repo = $this->manager->getRepository ($this->entityClass);
      $total = count ($repo->findAll ());
      // faire la division l'arrondi et renvoyer
      $pages = ceil($total / $this->limit);
      return $pages;
    }

    public function getData()
    {
      // calcul de l'offset
      $offset = $this->currentPage * $this->limit - $this->limit;
      //Demander au repo les data
      $repo = $this->manager->getRepository ( $this->entityClass );
      $data = $repo->findBy ( [] , [] , $this->limit , $offset );
      //envoyer les éléments
      return $data;
    }


    public function getEntityClass()
    {
      return $this->entityClass;
    }

    public function setEntityClass($entityClass)
    {
      $this->entityClass = $entityClass;
      return $this;
    }

    public function getLimit()
    {
      return $this->limit;
    }


    public function setLimit($limit)
    {
      $this->limit = $limit;
      return $this;

    }


    public function getCurrentPage()
    {
      return $this->currentPage;
    }


    public function setCurrentPage($currentPage)
    {
      $this->currentPage = $currentPage;
      return $this;
    }


  }