<?php

namespace App\Controller;

use App\Controller\Trait\RoleTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/me', name: 'me-')]
class MeController extends AbstractController
{
    use RoleTrait;

    #[Route('/', name: 'main')]
    public function index(): Response
    {
        if ($response = $this->checkRole('ROLE_USER')) {
            return $response;
        }

        return $this->render('me/index.html.twig', []);
    }
}
