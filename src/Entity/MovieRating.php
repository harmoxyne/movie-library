<?php

namespace App\Entity;

use App\Repository\MovieRatingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MovieRatingRepository::class)
 */
class MovieRating
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $imdb;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $rottenTomatto;

    /**
     * @ORM\OneToOne(targetEntity=Movie::class, inversedBy="movieRating", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $movie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImdb(): ?float
    {
        return $this->imdb;
    }

    public function setImdb(?float $imdb): self
    {
        $this->imdb = $imdb;

        return $this;
    }

    public function getRottenTomatto(): ?float
    {
        return $this->rottenTomatto;
    }

    public function setRottenTomatto(?float $rottenTomatto): self
    {
        $this->rottenTomatto = $rottenTomatto;

        return $this;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }
}
