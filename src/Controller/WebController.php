<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WebController extends AbstractController
{
    public function __construct(private readonly ArticleRepository $repository)
    {
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $articles = $this->repository->findAll();

        $articles = array_map(function (array $row) {
            $row['tagsArray'] = $row['tags'] ? explode(',', $row['tags']) : [];
            return $row;
        }, $articles);

        return $this->render('index.html.twig', ['articles' => $articles]);
    }

    #[Route('/article/{slug}', name: 'article_show', requirements: ['slug' => '.+'])]
    public function show(string $slug): Response
    {
        $article = $this->repository->findBySlug($slug);

        if ($article === null) {
            throw $this->createNotFoundException("Article '{$slug}' not found.");
        }

        $article['tagsArray'] = $article['tags'] ? explode(',', $article['tags']) : [];

        return $this->render('article.html.twig', ['article' => $article]);
    }
}
