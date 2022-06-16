<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\Common\Collections\Criteria;
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

    #[Route('/{id<\d+>}', name: 'byId')]
    public function postById(Post $post): Response
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('parent_post', null))
            ->orderBy(['date' => 'desc'])
            ->setMaxResults(5);

        return $this->render('post/post.html.twig', [
            'post' => $post,
            'comments' => $post->getComments()->matching($criteria),
        ]);
    }

    #[Route('/{slug}', name: 'bySlug')]
    public function postBySlug(Post $post): Response
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('parent_post', null))
            ->orderBy(['date' => 'desc'])
            ->setMaxResults(5);

        return $this->render('post/post.html.twig', [
            'post' => $post,
            'comments' => $post->getComments()->matching($criteria),
        ]);
    }
}
