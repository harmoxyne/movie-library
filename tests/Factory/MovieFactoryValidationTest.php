<?php

namespace App\Tests\Factory;

use App\Entity\User;
use App\Exception\ValidationException;
use App\Factory\MovieFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MovieFactoryValidationTest extends KernelTestCase
{
    public function testCorrectRequestPassValidation(): void
    {
        $request = [
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

        /** @var ValidatorInterface $validator */
        $validator = static::getContainer()->get(ValidatorInterface::class);

        $movieFactory = new MovieFactory($validator, $this->getEntityManagerMock());

        self::assertNotEmpty($movieFactory->createFromRequest($this->getUserMock(), $request));
    }

    public function testEmptyRatingsPassValidation(): void
    {
        $request = [
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

        /** @var ValidatorInterface $validator */
        $validator = static::getContainer()->get(ValidatorInterface::class);

        $movieFactory = new MovieFactory($validator, $this->getEntityManagerMock());

        self::assertNotEmpty($movieFactory->createFromRequest($this->getUserMock(), $request));
    }

    public function testMissingNameThrowsValidationException(): void
    {
        $this->expectException(ValidationException::class);

        /** @var ValidatorInterface $validator */
        $validator = static::getContainer()->get(ValidatorInterface::class);

        $movieFactory = new MovieFactory($validator, $this->getEntityManagerMock());
        $request = [
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

        $movieFactory->createFromRequest($this->getUserMock(), $request);
    }

    public function testMissingDirectorThrowsValidationException(): void
    {
        $this->expectException(ValidationException::class);

        /** @var ValidatorInterface $validator */
        $validator = static::getContainer()->get(ValidatorInterface::class);

        $movieFactory = new MovieFactory($validator, $this->getEntityManagerMock());
        $request = [
            'name' => 'The Titanic',
            'release_date' => '18-01-1998',
            'casts' => [
                'DiCaprio',
                'Kate Winslet',
            ],
            'ratings' => [
                'imdb' => 7.8,
                'rotten_tomatto' => 8.2,
            ],
        ];

        $movieFactory->createFromRequest($this->getUserMock(), $request);
    }

    public function testMissingReleaseDateThrowsValidationException(): void
    {
        $this->expectException(ValidationException::class);

        /** @var ValidatorInterface $validator */
        $validator = static::getContainer()->get(ValidatorInterface::class);

        $movieFactory = new MovieFactory($validator, $this->getEntityManagerMock());
        $request = [
            'name' => 'The Titanic',
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

        $movieFactory->createFromRequest($this->getUserMock(), $request);
    }

    public function testMissingCastsThrowsValidationException(): void
    {
        $this->expectException(ValidationException::class);

        /** @var ValidatorInterface $validator */
        $validator = static::getContainer()->get(ValidatorInterface::class);

        $movieFactory = new MovieFactory($validator, $this->getEntityManagerMock());
        $request = [
            'name' => 'The Titanic',
            'release_date' => '18-01-1998',
            'director' => 'James Cameron',
            'ratings' => [
                'imdb' => 7.8,
                'rotten_tomatto' => 8.2,
            ],
        ];

        $movieFactory->createFromRequest($this->getUserMock(), $request);
    }

    public function testMissingRatingsThrowsValidationException(): void
    {
        $this->expectException(ValidationException::class);

        /** @var ValidatorInterface $validator */
        $validator = static::getContainer()->get(ValidatorInterface::class);

        $movieFactory = new MovieFactory($validator, $this->getEntityManagerMock());
        $request = [
            'name' => 'The Titanic',
            'release_date' => '18-01-1998',
            'director' => 'James Cameron',
            'casts' => [
                'DiCaprio',
                'Kate Winslet',
            ],
        ];

        $movieFactory->createFromRequest($this->getUserMock(), $request);
    }

    public function testEmptyCastsThrowsValidationException(): void
    {
        $this->expectException(ValidationException::class);

        /** @var ValidatorInterface $validator */
        $validator = static::getContainer()->get(ValidatorInterface::class);

        $movieFactory = new MovieFactory($validator, $this->getEntityManagerMock());
        $request = [
            'name' => 'The Titanic',
            'release_date' => '18-01-1998',
            'director' => 'James Cameron',
            'casts' => [],
            'ratings' => [
                'imdb' => 7.8,
                'rotten_tomatto' => 8.2,
            ],
        ];

        $movieFactory->createFromRequest($this->getUserMock(), $request);
    }

    private function getEntityManagerMock(): EntityManagerInterface
    {
        return $this->createMock(EntityManagerInterface::class);
    }

    private function getUserMock(): User
    {
        return $this->createMock(User::class);
    }
}
