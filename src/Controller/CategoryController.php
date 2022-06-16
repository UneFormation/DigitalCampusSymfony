<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category', name: 'category-')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll()
        ]);
    }

    #[Route('/{id<\d+>}', name: 'byId')]
    public function categoryById(Category $category): Response
    {
        return $this->render('category/category.html.twig', [
            'category' => $category,
            'posts' => $category->getPosts(),
        ]);
    }

    #[Route('/{slug}', name: 'bySlug')]
    public function categoryBySlug(Category $category): Response
    {
        return $this->render('category/category.html.twig', [
            'category' => $category,
            'posts' => $category->getPosts(),
        ]);
    }
}
