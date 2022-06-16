<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\PostComment;
use App\Entity\User;
use App\Form\PostCommentFormType;
use App\Repository\PostRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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
    #[Route('/{slug}', name: 'bySlug')]
    public function postView(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $postComment = new PostComment();
        $form = $this->createForm(PostCommentFormType::class, $postComment);
        $form->handleRequest($request);

        if ($this->getUser() && $form->isSubmitted() && $form->isValid()) {
            /** @var User $author */
            $author = $this->getUser();
            $postComment->setDate(new \DateTimeImmutable());
            $postComment->setAuthor($author);

            $post->addComment($postComment);

            $entityManager->persist($postComment);
            $entityManager->flush();

            $postComment = new PostComment();
            $form = $this->createForm(PostCommentFormType::class, $postComment);
        }

        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('parent_post', null))
            ->orderBy(['date' => 'desc'])
            ->setMaxResults(5);

        return $this->render('post/post.html.twig', [
            'post' => $post,
            'postCommentForm' => $form->createView(),
            'comments' => $post->getComments()->matching($criteria),
        ]);
    }
}
