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

  /**
   * Permet a l'admin de supprimer un commentaire
   *
   * @Route("/admin/comments/{id}/delete", name="admin_comment_delete")
   *
   * @param Comment $comment
   * @param ObjectManager $manager
   * @return Response
   */
    public function delete(Comment $comment, ObjectManager $manager) {
      $manager -> remove ($comment);
      $manager -> flush ();

      $this->addFlash ('success', "Le commentaire {$comment->getId ()} a été supprimé");

      return $this->redirectToRoute ('admin_comment_index');
    }

}
