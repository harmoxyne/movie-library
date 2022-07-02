<?php

namespace App\Factory;

use App\Entity\Movie;
use App\Entity\MovieCast;
use App\Entity\MovieRating;
use App\Entity\User;
use App\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class MovieFactory
{
    private ValidatorInterface $validator;
    private EntityManagerInterface $entityManager;

    public function __construct(ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $this->validator = $validator;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws ValidationException
     */
    public function createFromRequest(User $user, array $request): Movie
    {
        $this->validateRequest($request);

        $movie = (new Movie())
            ->setName($request['name'])
            ->setDirector($request['director'])
            ->setReleaseDate($request['release_date'])
            ->setUser($user);

        $this->createMovieCasts($movie, $request['casts']);
        $this->createMovieRating($movie, $request['ratings']);

        $this->entityManager->persist($movie);
        $this->entityManager->flush();

        return $movie;
    }

    private function createMovieCasts(Movie $movie, array $casts): void
    {
        foreach ($casts as $cast) {
            $castEntity = (new MovieCast())
                ->setName($cast)
                ->setMovie($movie);

            $movie->addMovieCast($castEntity);

            $this->entityManager->persist($castEntity);
        }
    }

    private function createMovieRating(Movie $movie, array $ratings): void
    {
        $movieRating = (new MovieRating())
            ->setImdb($ratings['imdb'])
            ->setRottenTomatto($ratings['rotten_tomatto']);

        $movie->setMovieRating($movieRating);

        $this->entityManager->persist($movieRating);
    }

    /**
     * @throws ValidationException
     */
    private function validateRequest(array $request): void
    {
        $constraint = new Assert\Collection([
            'name' => [
                new Assert\NotBlank(),
                new Assert\Type('string'),
            ],
            'release_date' => [
                new Assert\NotBlank(),
                new Assert\Type('string'),
                new Assert\Regex('/\d{2}-\d{2}-\d{4}/'),
            ],
            'director' => [
                new Assert\NotBlank(),
                new Assert\Type('string'),
            ],
            'casts' => [
                new Assert\Type('array'),
                new Assert\Count(['min' => 1]),
                new Assert\All([
                    new Assert\NotBlank(),
                    new Assert\Type('string'),
                ]),
            ],
            'ratings' => new Assert\Collection([
                'imdb' => new Assert\Optional([
                    new Assert\Type('float'),
                ]),
                'rotten_tomatto' => new Assert\Optional([
                    new Assert\Type('float'),
                ]),
            ]),
        ]);

        $validationErrors = $this->validator->validate($request, $constraint);

        if ($validationErrors->count()) {
            $errors = [];
            for ($i = 0; $i < $validationErrors->count(); $i++) {
                // We get value in format "[field_name]" from validator, so lets remove brackets
                $errorKey = trim($validationErrors->get($i)->getPropertyPath(), '[]');

                $errorMessage = $validationErrors->get($i)->getMessage();

                $errors[$errorKey][] = $errorMessage;
            }
            throw new ValidationException($errors);
        }
    }
}
