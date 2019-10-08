<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCommentController extends AbstractController
{
  /**
   * @Route("/admin/comments", name="admin_comment_index")
   * @param CommentRepository $repository
   * @return Response
   */
    public function index(CommentRepository $repository)
    {
        return $this->render('admin/comment/index.html.twig', [
            'comments' => $repository->findAll ()
        ]);
    }

  /**
   * permet d'éditer un commentaire côté admin
   *
   * @Route("/admin/comments/{id}/edit", name="admin_comment_edit")
   *
   * @param Comment $comment
   * @param Request $request
   * @param ObjectManager $manager
   *
   * @return Response
   */
    public function edit(Comment $comment, Request $request, ObjectManager $manager): Response
    {
      $form = $this->createForm (AdminCommentType::class, $comment);
      $form -> handleRequest ($request);

      if ($form->isSubmitted () && $form->isValid ()) {
        $manager->persist ($comment);
        $manager->flush ();

        $this->addFlash ('success', "Commentaire modifié");
      }
      return $this->render ('admin/comment/edit.html.twig', [
        'comment' => $comment,
        'form' => $form->createView ()
      ]);

    }

}
