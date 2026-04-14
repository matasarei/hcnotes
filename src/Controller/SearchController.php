<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class SearchController extends AbstractController
{
    public function __construct(private readonly ArticleRepository $repository)
    {
    }

    #[Route('/api/search', name: 'api_search', methods: ['GET'])]
    public function search(Request $request): JsonResponse
    {
        $query = trim($request->query->getString('q', ''));

        if ($query === '') {
            return $this->json(['error' => 'Missing query parameter "q"'], 400);
        }

        $results = $this->repository->search($query);

        $formatted = array_map(fn(array $row) => [
            'slug'        => $row['slug'],
            'title'       => $row['title'],
            'description' => $row['description'],
            'tags'        => $row['tags'] ? explode(',', $row['tags']) : [],
            'date'        => $row['date'],
        ], $results);

        return $this->json($formatted);
    }
}
