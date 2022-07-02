<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\MovieCast;
use App\Entity\MovieRating;
use App\Exception\ValidationException;
use App\Factory\MovieFactory;
use App\Repository\MovieRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1")
 */
class MovieController extends AbstractController
{
    /**
     * @Route("/movies", name="app_movies", methods={"GET"})
     */
    public function index(MovieRepository $movieRepository): JsonResponse
    {
        $movieEntities = $movieRepository->findBy([]);

        $movies = [];
        foreach ($movieEntities as $movieEntity) {
            $movies[] = $this->formatMovie($movieEntity);
        }

        return $this->json($movies);
    }

    /**
     * @Route("/movies/{id}", name="app_movie_show", methods={"GET"})
     */
    public function show(int $id, MovieRepository $movieRepository): JsonResponse
    {
        $movie = $movieRepository->find($id);

        if ($movie === null) {
            return $this->json([
                'errors' => [
                    "Movie with id #$id not found",
                ],
            ], Response::HTTP_NOT_FOUND);
        }
        return $this->json($this->formatMovie($movie));
    }

    /**
     * @Route("/movies", name="app_movie_add", methods={"POST"})
     */
    public function add(Request $request, MovieFactory $movieFactory): JsonResponse
    {
        try {
            $movie = $movieFactory->createFromRequest($request->toArray());
        } catch (ValidationException $exception) {
            return $this->json([
                'errors' => $exception->getErrors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($this->formatMovie($movie), Response::HTTP_CREATED);
    }

    private function formatMovie(Movie $movie): array
    {
        return [
            'id' => $movie->getId(),
            'name' => $movie->getName(),
            'director' => $movie->getDirector(),
            'release_date' => $movie->getReleaseDate(),
            'casts' => $this->formatMovieCasts($movie->getMovieCasts()),
            'ratings' => $this->formatMovieRating($movie->getMovieRating()),
        ];
    }

    /**
     * @param $movieCast Collection<int, MovieCast>
     * @return array
     */
    private function formatMovieCasts(Collection $movieCast): array
    {
        $result = [];
        foreach ($movieCast as $cast) {
            $result[] = $cast->getName();
        }

        return $result;
    }

    private function formatMovieRating(MovieRating $movieRating): array
    {
        return [
            'imdb' => $movieRating->getImdb(),
            'rotten_tomatto' => $movieRating->getRottenTomatto(),
        ];
    }
}
