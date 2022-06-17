<?php

namespace App\Controller;

use App\Controller\Trait\RoleTrait;
use App\Form\MeFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/me', name: 'me-')]
class MeController extends AbstractController
{
    use RoleTrait;

    #[Route('/', name: 'main')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($response = $this->checkRole('ROLE_USER')) {
            return $response;
        }
        $user = $this->getUser();
        $form = $this->createForm(MeFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->render('me/index.html.twig', [
            'userForm' => $form->createView()
        ]);
    }
}
