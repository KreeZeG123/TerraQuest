<?php

namespace App\Entity;

use App\Repository\AreaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: AreaRepository::class)]
#[UniqueEntity('title')]
#[UniqueEntity('slug')]
class Area
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $title = "";

    #[ORM\Column(length: 255)]
    private string $slug = "";

    #[ORM\Column(length: 255, nullable: true)]
    private string $infos = "";

    #[ORM\Column]
    private float $latGPS = 0;

    #[ORM\Column]
    private float $lngGPS = 0;

    #[ORM\Column]
    private float $radius = 0;

    /**
     * @var Collection<int, Challenge>
     */
    #[ORM\OneToMany(targetEntity: Challenge::class, mappedBy: 'area', orphanRemoval: true)]
    private Collection $challenges;

    /**
     * @var Collection<int, Species>
     */
    #[ORM\OneToMany(targetEntity: Species::class, mappedBy: 'area')]
    private Collection $species;

    public function __construct()
    {
        $this->challenges = new ArrayCollection();
        $this->species = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getInfos(): string
    {
        return $this->infos;
    }

    public function setInfos(string $infos): static
    {
        $this->infos = $infos;

        return $this;
    }

    public function getLatGPS(): float
    {
        return $this->latGPS;
    }

    public function setLatGPS(float $latGPS): static
    {
        $this->latGPS = $latGPS;

        return $this;
    }

    public function getLngGPS(): float
    {
        return $this->lngGPS;
    }

    public function setLngGPS(float $lngGPS): static
    {
        $this->lngGPS = $lngGPS;

        return $this;
    }

    public function getRadius(): float
    {
        return $this->radius;
    }

    public function setRadius(float $radius): static
    {
        $this->radius = $radius;

        return $this;
    }

    /**
     * @return Collection<int, Challenge>
     */
    public function getChallenges(): Collection
    {
        return $this->challenges;
    }

    public function addChallenge(Challenge $challenge): static
    {
        if (!$this->challenges->contains($challenge)) {
            $this->challenges->add($challenge);
            $challenge->setArea($this);
        }

        return $this;
    }

    public function removeChallenge(Challenge $challenge): static
    {
        if ($this->challenges->removeElement($challenge)) {
            // set the owning side to null (unless already changed)
            if ($challenge->getArea() === $this) {
                $challenge->setArea(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Species>
     */
    public function getSpecies(): Collection
    {
        return $this->species;
    }

    public function addSpecies(Species $species): static
    {
        if (!$this->species->contains($species)) {
            $this->species->add($species);
            $species->setArea($this);
        }

        return $this;
    }

    public function removeSpecies(Species $species): static
    {
        if ($this->species->removeElement($species)) {
            // set the owning side to null (unless already changed)
            if ($species->getArea() === $this) {
                $species->setArea(null);
            }
        }

        return $this;
    }
}
