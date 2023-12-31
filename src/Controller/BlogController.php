<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ArticleRepository;
use App\Repository\TypeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    #[Route('/')] // on définit la route / (donc route par défaut de tout le site) pour cette fonction ShowHelloWorld
    public function ShowHelloWorld(): Response
    {
        return new Response('Hello World'); // on ne fait qu'afficher Hello World
    }

    #[Route('/blog/{id}/{name}', name: 'app_blog', requirements: ["id" => "\d{2,6}", "name" => "[a-zA-Z]{5,50}"])]
    public function index(int $id, string $name): Response
    {
        return $this->render('blog/index.html.twig', [
            'id' => $id,
            'name' => $name,
        ]);
    }

    #[Route('/blog/hello', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('blog/home.html.twig', []);
    }

    #[Route('/blog/articles', name: 'app_blog_articles')]
    public function showArticles(ArticleRepository $repoArticle, CategoryRepository $repoCategory): Response
    {
        $articles = $repoArticle->findAll();
        $categories = $repoCategory->findAll();

        return $this->render('blog/index.html.twig', [
            'articles' => $articles,
            'categories' => $categories,
        ]);
    }

    #[Route('/article/{slug}', name: 'app_single_article')]
    public function single(CategoryRepository $repoCategory, ArticleRepository $repoArticle, string $slug): Response
    {
        $article = $repoArticle->findOneBySlug($slug);
        $categories = $repoCategory->findAll();
        return $this->render('blog/single.html.twig', ['article' => $article, 'categories' => $categories]);
    }

    /*#[Route('/category/{slug}', name: 'app_articles_by_category')]
    public function articlesByCategory(CategoryRepository $repoCategory, int $slug):Response{
        $categories = $repoCategory->findBySlug($slug);
        return $this->render('blog/index.html.twig', ['categories' => $categories]);
    }*/

    #[Route('/articles/category/{slug}', name: 'app_articles_by_category')]
    public function articlesByCategory(string $slug, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBySlug($slug);
        $categories = $categoryRepository->findAll();
        $articles = [];

        if ($category) {
            $articles = $category->getArticles();
        }

        return $this->render('blog/articles_by_category.html.twig', [
            'articles' => $articles,
            'categories' => $categories,
            'categoryName' => $category->getName(),
        ]);
    }

    #[Route('blog/type/{type}', name: 'app_artiste_by_type')]
    public function typesByCategory(TypeRepository $typeRepository, string $type): Response
    {
        $artiste = [];
        $type = $typeRepository->findOneByType($type);
        $types = $typeRepository->findAll();
        if ($type != null) {
            $artiste = $type->getArtiste();
        }

        return $this->render('blog/articles_by_category.html.twig', ['artiste' => $artiste, 'types' => $types, 'type' => $type->getName(),]);
    }
}
