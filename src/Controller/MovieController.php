<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1")
 */
class MovieController extends AbstractController
{
    /**
     * @Route("/movies", name="app_movies", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'We will have movies list here',
        ]);
    }

    /**
     * @Route("/movies/{id}", name="app_movie_show", methods={"GET"})
     */
    public function show(int $id): JsonResponse
    {
        return $this->json([
            'message' => "We will have movie detail for id $id here",
        ]);
    }

    /**
     * @Route("/movies", name="app_movie_add", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        return $this->json([
            'message' => 'We will add movie here',
        ]);
    }
}
