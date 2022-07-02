<?php

namespace App\Tests\Factory;

use App\Entity\Movie;
use App\Entity\MovieCast;
use App\Entity\MovieRating;
use App\Entity\User;
use App\Factory\MovieFactory;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\Builder\InvocationMocker;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MovieFactoryCreationTest extends KernelTestCase
{
    public function testCorrectNameFieldSetting(): void
    {
        $expectedName = 'The Titanic';
        $request = [
            'name' => $expectedName,
            'director' => '',
            'release_date' => '',
            'casts' => [],
            'ratings' => [
                'imdb' => null,
                'rotten_tomatto' => null,
            ],
        ];

        $entityManagerMock = $this->createMock(EntityManagerInterface::class);

        $entityManagerMock
            ->method('persist')
            ->with($this->callback(function ($entity) use ($expectedName) {
                if (!($entity instanceof Movie)) {
                    return true;
                }

                self::assertEquals($expectedName, $entity->getName());

                return true;
            }));

        $movieFactory = new MovieFactory($this->getValidatorMock(), $entityManagerMock);

        $movieFactory->createFromRequest($this->getUserMock(), $request);
    }

    public function testCorrectDirectorFieldSetting(): void
    {
        $expectedDirector = 'James Cameron';
        $request = [
            'name' => '',
            'director' => $expectedDirector,
            'release_date' => '',
            'casts' => [],
            'ratings' => [
                'imdb' => null,
                'rotten_tomatto' => null,
            ],
        ];

        $entityManagerMock = $this->createMock(EntityManagerInterface::class);

        $entityManagerMock
            ->method('persist')
            ->with($this->callback(function ($entity) use ($expectedDirector) {
                if (!($entity instanceof Movie)) {
                    return true;
                }

                self::assertEquals($expectedDirector, $entity->getDirector());

                return true;
            }));

        $movieFactory = new MovieFactory($this->getValidatorMock(), $entityManagerMock);

        $movieFactory->createFromRequest($this->getUserMock(), $request);
    }

    public function testCorrectReleaseDateFieldSetting(): void
    {
        $expectedReleaseDate = '18-01-1998';
        $request = [
            'name' => '',
            'director' => '',
            'release_date' => $expectedReleaseDate,
            'casts' => [],
            'ratings' => [
                'imdb' => null,
                'rotten_tomatto' => null,
            ],
        ];

        $entityManagerMock = $this->createMock(EntityManagerInterface::class);

        $entityManagerMock
            ->method('persist')
            ->with($this->callback(function ($entity) use ($expectedReleaseDate) {
                if (!($entity instanceof Movie)) {
                    return true;
                }

                self::assertEquals($expectedReleaseDate, $entity->getReleaseDate());

                return true;
            }));

        $movieFactory = new MovieFactory($this->getValidatorMock(), $entityManagerMock);

        $movieFactory->createFromRequest($this->getUserMock(), $request);
    }

    public function testCorrectCastsSetting(): void
    {
        $expectedCast = 'DiCaprio';
        $request = [
            'name' => '',
            'director' => '',
            'release_date' => '',
            'casts' => [
                $expectedCast,
            ],
            'ratings' => [
                'imdb' => null,
                'rotten_tomatto' => null,
            ],
        ];

        $entityManagerMock = $this->createMock(EntityManagerInterface::class);

        $entityManagerMock
            ->method('persist')
            ->with($this->callback(function ($entity) use ($expectedCast) {
                if (!($entity instanceof MovieCast)) {
                    return true;
                }

                self::assertEquals($expectedCast, $entity->getName());

                return true;
            }));

        $movieFactory = new MovieFactory($this->getValidatorMock(), $entityManagerMock);

        $movieFactory->createFromRequest($this->getUserMock(), $request);
    }

    public function testCorrectRatingSetting(): void
    {
        $expectedIMDBRating = 7.8;
        $expectedRottenTomattoRating = 8.2;
        $request = [
            'name' => '',
            'director' => '',
            'release_date' => '',
            'casts' => [],
            'ratings' => [
                'imdb' => $expectedIMDBRating,
                'rotten_tomatto' => $expectedRottenTomattoRating,
            ],
        ];

        $entityManagerMock = $this->createMock(EntityManagerInterface::class);

        $entityManagerMock
            ->method('persist')
            ->with($this->callback(function ($entity) use ($expectedIMDBRating, $expectedRottenTomattoRating) {
                if (!($entity instanceof MovieRating)) {
                    return true;
                }

                self::assertEquals($expectedIMDBRating, $entity->getImdb());
                self::assertEquals($expectedRottenTomattoRating, $entity->getRottenTomatto());

                return true;
            }));

        $movieFactory = new MovieFactory($this->getValidatorMock(), $entityManagerMock);

        $movieFactory->createFromRequest($this->getUserMock(), $request);
    }

    /**
     * @return InvocationMocker|ValidatorInterface
     */
    private function getValidatorMock()
    {
        $mock = $this->getMockBuilder(ValidatorInterface::class)->getMock();

        $mock->method('validate')
            ->willReturn(new class implements \Countable {
                public function count(): int
                {
                    return 0;
                }
            });

        return $mock;
    }

    private function getUserMock(): User
    {
        return $this->createMock(User::class);
    }
}
