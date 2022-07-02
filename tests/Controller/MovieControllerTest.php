<?php

namespace App\Tests\Controller;

use App\Factory\MovieFactory;
use JsonException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class MovieControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
    }

    /**
     * @throws JsonException
     */
    public function testSuccessfulCreatingMovieObject(): void
    {
        $expectedName = 'The Titanic';
        $expectedReleaseDate = '18-01-1998';
        $expectedDirector = 'James Cameron';
        $expectedCasts = [
            'DiCaprio',
            'Kate Winslet',
        ];
        $expectedRatings = [
            'imdb' => 7.8,
            'rotten_tomatto' => 8.2,
        ];

        $payload = [
            'name' => $expectedName,
            'release_date' => $expectedReleaseDate,
            'director' => $expectedDirector,
            'casts' => $expectedCasts,
            'ratings' => $expectedRatings,
        ];

        $response = $this->callCreateMovie($payload);

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $responseData = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertArrayHasKey('id', $responseData, 'Response does not contain "id" field');
        self::assertArrayHasKey('name', $responseData, 'Response does not contain "name" field');
        self::assertArrayHasKey('release_date', $responseData, 'Response does not contain "release_date" field');
        self::assertArrayHasKey('director', $responseData, 'Response does not contain "director" field');
        self::assertArrayHasKey('casts', $responseData, 'Response does not contain "casts" array');
        self::assertArrayHasKey('ratings', $responseData, 'Response does not contain "ratings" object');

        self::assertEquals($expectedName, $responseData['name'],
            'Response contain "name" with different value than expected');
        self::assertEquals($expectedReleaseDate, $responseData['release_date'],
            'Response contain "release_date" with different value than expected');
        self::assertEquals($expectedDirector, $responseData['director'],
            'Response contain "director" with different value than expected');

        self::assertCount(count($expectedCasts), $responseData['casts'],
            'Response contain different count of "casts" than expected');
        self::assertSame($expectedCasts, $responseData['casts'],
            'Response contain different values in "casts" than expected');

        self::assertArrayHasKey('imdb', $responseData['ratings'], 'Response "ratings" does not contain "imdb" field');
        self::assertArrayHasKey('rotten_tomatto', $responseData['ratings'],
            'Response "ratings" does not contain "rotten_tomatto" field');

        self::assertEquals($expectedRatings['imdb'], $responseData['ratings']['imdb'],
            'Response "ratings.imdb" contain different value than expected');
        self::assertEquals($expectedRatings['rotten_tomatto'], $responseData['ratings']['rotten_tomatto'],
            'Response "ratings.rotten_tomatto" contain different value than expected');
    }

    public function invalidRequestDataProvider(): array
    {
        $correctPayload = [
            'name' => 'The Titanic',
            'release_date' => '18-01-1998',
            'director' => 'James Cameron',
            'casts' => [
                'DiCaprio',
                'Kate Winslet',
            ],
            'ratings' => [
                'imdb' => 7.8,
                'rotten_tomatto' => 8.2,
            ],
        ];

        return [
            [array_diff_key($correctPayload, ['name' => '']), 'name', 'This field is missing.'],
            [array_diff_key($correctPayload, ['release_date' => '']), 'release_date', 'This field is missing.'],
            [array_diff_key($correctPayload, ['director' => '']), 'director', 'This field is missing.'],
            [array_diff_key($correctPayload, ['casts' => '']), 'casts', 'This field is missing.'],
            [
                array_merge($correctPayload, ['release_date' => 'wrong_date']),
                'release_date',
                'This value is not valid.',
            ],
            [
                array_merge($correctPayload, ['casts' => []]),
                'casts',
                'This collection should contain 1 element or more.',
            ],
        ];
    }

    /**
     * @dataProvider invalidRequestDataProvider
     */
    public function testCreateMovieWithMissingFields(
        array $payload,
        string $expectedErrorKey,
        string $expectedErrorMessage
    ): void {
        $response = $this->callCreateMovie($payload);

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $responseData = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertArrayHasKey('errors', $responseData, 'Response does not contain "errors" field');
        self::assertArrayHasKey($expectedErrorKey, $responseData['errors'],
            "Response does not contain \"errors.$expectedErrorKey\" field");

        self::assertCount(1, $responseData['errors'][$expectedErrorKey],
            "Response contain different amount of errors for $expectedErrorKey than expected");

        self::assertEquals($expectedErrorMessage, $responseData['errors'][$expectedErrorKey][0],
            "Response contain different error for $expectedErrorKey than expected");
    }

    public function testCreateMovieWithoutRatings(): void
    {
        $payload = [
            'name' => 'The Titanic',
            'release_date' => '18-01-1998',
            'director' => 'James Cameron',
            'casts' => [
                'DiCaprio',
                'Kate Winslet',
            ],
            'ratings' => [
                'imdb' => null,
                'rotten_tomatto' => null,
            ],
        ];

        $response = $this->callCreateMovie($payload);

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $responseData = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertArrayHasKey('ratings', $responseData, 'Response does not contain "ratings" object');

        self::assertArrayHasKey('imdb', $responseData['ratings'], 'Response "ratings" does not contain "imdb" field');
        self::assertArrayHasKey('rotten_tomatto', $responseData['ratings'],
            'Response "ratings" does not contain "rotten_tomatto" field');

        self::assertNull($responseData['ratings']['imdb'],
            'Response "ratings.imdb" contain different value than expected');
        self::assertNull($responseData['ratings']['rotten_tomatto'],
            'Response "ratings.rotten_tomatto" contain different value than expected');
    }

    public function testSuccessfulShowingMovie(): void
    {
        $expectedName = 'The Titanic';
        $expectedReleaseDate = '18-01-1998';
        $expectedDirector = 'James Cameron';
        $expectedCasts = [
            'DiCaprio',
            'Kate Winslet',
        ];
        $expectedRatings = [
            'imdb' => 7.8,
            'rotten_tomatto' => 8.2,
        ];

        /** @var MovieFactory $movieFactory */
        $movieFactory = static::getContainer()->get(MovieFactory::class);

        $movie = $movieFactory->createFromRequest([
            'name' => $expectedName,
            'release_date' => $expectedReleaseDate,
            'director' => $expectedDirector,
            'casts' => $expectedCasts,
            'ratings' => $expectedRatings,
        ]);

        $response = $this->callShowMovie($movie->getId());

        self::assertResponseIsSuccessful();

        $responseData = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertArrayHasKey('id', $responseData, 'Response does not contain "id" field');
        self::assertArrayHasKey('name', $responseData, 'Response does not contain "name" field');
        self::assertArrayHasKey('release_date', $responseData, 'Response does not contain "release_date" field');
        self::assertArrayHasKey('director', $responseData, 'Response does not contain "director" field');
        self::assertArrayHasKey('casts', $responseData, 'Response does not contain "casts" array');
        self::assertArrayHasKey('ratings', $responseData, 'Response does not contain "ratings" object');

        self::assertEquals($expectedName, $responseData['name'],
            'Response contain "name" with different value than expected');
        self::assertEquals($expectedReleaseDate, $responseData['release_date'],
            'Response contain "release_date" with different value than expected');
        self::assertEquals($expectedDirector, $responseData['director'],
            'Response contain "director" with different value than expected');

        self::assertCount(count($expectedCasts), $responseData['casts'],
            'Response contain different count of "casts" than expected');
        self::assertSame($expectedCasts, $responseData['casts'],
            'Response contain different values in "casts" than expected');

        self::assertArrayHasKey('imdb', $responseData['ratings'], 'Response "ratings" does not contain "imdb" field');
        self::assertArrayHasKey('rotten_tomatto', $responseData['ratings'],
            'Response "ratings" does not contain "rotten_tomatto" field');

        self::assertEquals($expectedRatings['imdb'], $responseData['ratings']['imdb'],
            'Response "ratings.imdb" contain different value than expected');
        self::assertEquals($expectedRatings['rotten_tomatto'], $responseData['ratings']['rotten_tomatto'],
            'Response "ratings.rotten_tomatto" contain different value than expected');
    }

    public function testShowingNotExistingMovie(): void
    {
        $response = $this->callShowMovie(-1);

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);

        $responseData = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertArrayHasKey('errors', $responseData, 'Response does not contain "errors" array');
        self::assertCount(1, $responseData['errors'], 'Response contain different amount of errors than expected');

        self::assertEquals('Movie with id #-1 not found', $responseData['errors'][0],
            'Response contain different error text than expected');
    }

    public function testSuccessfulGetMovies(): void
    {
        $response = $this->callGetMovies();

        self::assertResponseIsSuccessful();

        $responseData = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        foreach ($responseData as $id => $movie) {
            self::assertArrayHasKey('id', $movie, 'Response does not contain "id" field for entry #'.$id);
            self::assertArrayHasKey('name', $movie, 'Response does not contain "name" field for entry #'.$id);
            self::assertArrayHasKey('release_date', $movie,
                'Response does not contain "release_date" field for entry #'.$id);
            self::assertArrayHasKey('director', $movie, 'Response does not contain "director" field for entry #'.$id);
            self::assertArrayHasKey('casts', $movie, 'Response does not contain "casts" array for entry #'.$id);
            self::assertArrayHasKey('ratings', $movie, 'Response does not contain "ratings" object for entry #'.$id);

        }
    }

    private function callCreateMovie(array $payload): Response
    {
        $this->client->request('POST', '/api/v1/movies', [], [], [], json_encode($payload));

        return $this->client->getResponse();
    }

    private function callShowMovie(int $id): Response
    {
        $this->client->request('GET', '/api/v1/movies/'.$id);

        return $this->client->getResponse();
    }

    private function callGetMovies(): Response
    {
        $this->client->request('GET', '/api/v1/movies');

        return $this->client->getResponse();
    }

}
