<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 */
class Movie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private ?string $releaseDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $director;

    /**
     * @ORM\OneToMany(targetEntity=MovieCast::class, mappedBy="movie")
     */
    private $movieCasts;

    /**
     * @ORM\OneToMany(targetEntity=MovieRating::class, mappedBy="movie")
     */
    private $movieRatings;

    public function __construct()
    {
        $this->movieCasts = new ArrayCollection();
        $this->movieRatings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getReleaseDate(): ?string
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(string $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getDirector(): ?string
    {
        return $this->director;
    }

    public function setDirector(string $director): self
    {
        $this->director = $director;

        return $this;
    }

    /**
     * @return Collection<int, MovieCast>
     */
    public function getMovieCasts(): Collection
    {
        return $this->movieCasts;
    }

    public function addMovieCast(MovieCast $movieCast): self
    {
        if (!$this->movieCasts->contains($movieCast)) {
            $this->movieCasts[] = $movieCast;
            $movieCast->setMovie($this);
        }

        return $this;
    }

    public function removeMovieCast(MovieCast $movieCast): self
    {
        if ($this->movieCasts->removeElement($movieCast)) {
            // set the owning side to null (unless already changed)
            if ($movieCast->getMovie() === $this) {
                $movieCast->setMovie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MovieRating>
     */
    public function getMovieRatings(): Collection
    {
        return $this->movieRatings;
    }

    public function addMovieRating(MovieRating $movieRating): self
    {
        if (!$this->movieRatings->contains($movieRating)) {
            $this->movieRatings[] = $movieRating;
            $movieRating->setMovie($this);
        }

        return $this;
    }

    public function removeMovieRating(MovieRating $movieRating): self
    {
        if ($this->movieRatings->removeElement($movieRating)) {
            // set the owning side to null (unless already changed)
            if ($movieRating->getMovie() === $this) {
                $movieRating->setMovie(null);
            }
        }

        return $this;
    }
}
