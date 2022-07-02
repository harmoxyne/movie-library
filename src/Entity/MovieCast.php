<?php

namespace App\Entity;

use App\Repository\MovieCastRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MovieCastRepository::class)
 */
class MovieCast
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Movie::class, inversedBy="movieCasts")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Movie $movie;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
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
}
