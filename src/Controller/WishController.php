<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Wish;
use App\Form\WishFormType;
use App\Helper\Censurator;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\OrderBy;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/wish', name: 'wish')]
class WishController extends AbstractController
{

  #[Route('/list', name: '_list')]
  public function list(WishRepository $wishRepository): Response
  {

    $wishes = $wishRepository->findBy(['isPublished' => true], ['dateCreated' => 'DESC']);

    return $this->render('wish/main.html.twig', [
      'wishes' => $wishes
    ]);
  }

  #[Route('/wish/{id}', name: '_detail')]
  public function detail(WishRepository $wishRepository, int $id): Response
  {
    $wish = $wishRepository->find($id);

    if (!$wish) {
      throw $this->createNotFoundException("Wish not found");
    }

    return $this->render('wish/detail.html.twig', [
      'wish' => $wish
    ]);
  }

  #[Route('/create', name: '_create')]
  #[IsGranted('ROLE_USER')]
  public function create(Request $request, EntityManagerInterface $em, #[CurrentUser] ?User $user, Censurator $censurator): Response
  {
    if (!$user) {
      throw $this->createAccessDeniedException("Vous devez être connecté pour ajouter un souhait.");
    }

    $wish = new Wish();
    $wish->setUser($user); // Associe l'utilisateur connecté au wish
    $form = $this->createForm(WishFormType::class, $wish);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $wish->setTitle($censurator->purify($wish->getTitle()));
      $wish->setDecription($censurator->purify($wish->getDecription()));
      $wish->setPublished(true);
      $em->persist($wish);
      $em->flush();

      $this->addFlash('success', 'Votre wish a été créé avec succès');
      return $this->redirectToRoute('wish_list');
    }

    return $this->render('wish/edit.html.twig', [
      'form' => $form
    ]);
  }

  #[Route('/update/{id}', name: '_update', requirements: ['id' => '\d+'])]
  #[IsGranted('ROLE_USER')]
  public function update(Request $request, EntityManagerInterface $em, Wish $wish): Response
  {


    $form = $this->createForm(WishFormType::class, $wish);
    $form->handleRequest($request);

    if ($form->isSubmitted()) {
      $em->flush();

      $this->addFlash('success', 'votre wish a été modifié avec succes');
      return $this->redirectToRoute('wish_list');

    }

    return $this->render('wish/edit.html.twig', [
      'form' => $form


    ]);
  }

  #[Route('/delete/{id}', name: '_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
  #[IsGranted('ROLE_ADMIN')]
  public function delete(Request $request, EntityManagerInterface $em, Wish $wish): Response
  {
    if ($this->isCsrfTokenValid('delete' . $wish->getId(), $request->request->get('_token'))) {
      $em->remove($wish);
      $em->flush();
      $this->addFlash('success', 'Votre wish a été supprimé avec succès');
    } else {
      $this->addFlash('error', 'Token CSRF invalide, la suppression a échoué');
    }

    return $this->redirectToRoute('wish_list');
  }
}
