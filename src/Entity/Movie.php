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
     * @ORM\OneToOne(targetEntity=MovieRating::class, mappedBy="movie", cascade={"persist", "remove"})
     */
    private $movieRating;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="movies")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    public function __construct()
    {
        $this->movieCasts = new ArrayCollection();
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

    public function getMovieRating(): MovieRating
    {
        return $this->movieRating;
    }

    public function setMovieRating(MovieRating $movieRating): self
    {
        // set the owning side of the relation if necessary
        if ($movieRating->getMovie() !== $this) {
            $movieRating->setMovie($this);
        }

        $this->movieRating = $movieRating;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
