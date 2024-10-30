<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Wish;
use App\Helper\Censurator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commentaire')]
class CommentaireController extends AbstractController
{
  #[Route('/create/{wishId}', name: 'commentaire_create')]
  public function create(Request $request, EntityManagerInterface $em, int $wishId,Censurator $censurator): Response
  {
    $wish = $em->getRepository(Wish::class)->find($wishId);

    if (!$wish) {
      throw $this->createNotFoundException('Wish not found');
    }

    $content = $request->request->get('content');
    $rating = $request->request->get('rating');

    if ($content) {
      $commentaire = new Commentaire();
      $commentaire->setContent($content);
      $commentaire->setRating($rating);
      $commentaire->setWish($wish);
      $commentaire->setAuthor($this->getUser());
      $commentaire->setContent($censurator->purify($commentaire->getContent()));

      $em->persist($commentaire);
      $em->flush();

      $this->addFlash('success', 'Votre commentaire a été ajouté !');
    }

    return $this->redirectToRoute('wish_detail', ['id' => $wish->getId()]);
  }



  #[Route('/edit/{id}', name: 'commentaire_edit')]
  public function edit(Request $request, EntityManagerInterface $em, Commentaire $commentaire): Response
  {
    if ($commentaire->getAuthor() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
      throw $this->createAccessDeniedException();
    }

    $content = $request->request->get('content');
    $rating = $request->request->get('rating');

    if ($content) {
      $commentaire->setContent($content);
      $commentaire->setRating($rating);
      $em->flush();

      $this->addFlash('success', 'Votre commentaire a été modifié !');
    }

    return $this->redirectToRoute('wish_detail', ['id' => $commentaire->getWish()->getId()]);
  }


  #[Route('/delete/{id}', name: 'commentaire_delete')]
  public function delete(EntityManagerInterface $em, Commentaire $commentaire): Response
  {
    if ($commentaire->getAuthor() === $this->getUser() || $this->isGranted('ROLE_ADMIN')) {
      $em->remove($commentaire);
      $em->flush();
      $this->addFlash('success', 'Le commentaire a été supprimé.');
    } else {
      throw $this->createAccessDeniedException();
    }

    return $this->redirectToRoute('wish_detail', ['id' => $commentaire->getWish()->getId()]);
  }

}