<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SitemapController extends AbstractController
{
    public function __construct(
        private readonly ArticleRepository $repository,
        private readonly string $siteBaseUrl,
    ) {
    }

    #[Route('/sitemap.xml', name: 'sitemap')]
    public function sitemap(): Response
    {
        $baseUrl = rtrim($this->siteBaseUrl, '/');
        $articles = $this->repository->findAll();

        $urls = [];

        // findAll() orders by date DESC, so the first row carries the newest date.
        $urls[] = [
            'loc'     => $baseUrl . '/',
            'lastmod' => $articles ? $this->lastmod($articles[0]) : null,
        ];
        $urls[] = ['loc' => $baseUrl . '/about', 'lastmod' => null];

        foreach ($articles as $article) {
            $urls[] = [
                'loc'     => $baseUrl . '/article/' . $article['slug'],
                'lastmod' => $this->lastmod($article),
            ];
        }

        $response = $this->render('sitemap.xml.twig', ['urls' => $urls]);
        $response->headers->set('Content-Type', 'application/xml; charset=UTF-8');

        return $response;
    }

    private function lastmod(array $article): ?string
    {
        if (!empty($article['date'])) {
            return $article['date'];
        }

        // updated_at is "Y-m-d H:i:s"; sitemap lastmod wants the date part.
        return isset($article['updated_at']) ? substr($article['updated_at'], 0, 10) : null;
    }
}
