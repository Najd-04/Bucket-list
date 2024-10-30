<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
  #[Route('/', name: 'app_main')]
  public function home(): Response
  {
    return $this->render('main/main.html.twig'
    );
  }

  #[Route('/about-us', name: 'app_about')]
  public function about(): Response
  {
    return $this->render('main/about.html.twig'
    );
  }
}
