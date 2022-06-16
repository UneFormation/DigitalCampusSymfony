<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/post', name: 'post-')]
class PostController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findBy(
                [],
                ['published_date' => 'desc'],
                12,
                0
            ),
        ]);
    }

    #[Route('/{id}', name: 'byId')]
    public function postById(Post $post): Response
    {
        return $this->render('post/post.html.twig', [
            'post' => $post
        ]);
    }
}
