<?php

namespace App\Entity;

use App\Repository\ChallengeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChallengeRepository::class)]
class Challenge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'challenges')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Area $area = null;

    /**
     * @var Collection<int, Species>
     */
    #[ORM\ManyToMany(targetEntity: Species::class)]
    private Collection $species;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 4095, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $hints = [];

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Species $speciesToGuess = null;

    public function __construct()
    {
        $this->species = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArea(): ?Area
    {
        return $this->area;
    }

    public function setArea(?Area $area): static
    {
        $this->area = $area;

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
        }

        return $this;
    }

    public function removeSpecies(Species $species): static
    {
        $this->species->removeElement($species);

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getHints(): array
    {
        return $this->hints;
    }

    public function setHints(array $hints): static
    {
        $this->hints = $hints;

        return $this;
    }

    public function getSpeciesToGuess(): ?Species
    {
        return $this->speciesToGuess;
    }

    public function setSpeciesToGuess(?Species $speciesToGuess): static
    {
        $this->speciesToGuess = $speciesToGuess;

        return $this;
    }
}
