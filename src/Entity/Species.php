<?php

namespace App\Entity;

use App\Repository\SpeciesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SpeciesRepository::class)]
#[UniqueEntity('slug')]
class Species
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    private string $latinName = "";

    #[ORM\Column(length: 255, nullable: true)]
    private string $commonName = "";

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    private string $slug = "";

    #[ORM\Column(length: 255, nullable: true)]
    private string $origin = "";

    #[ORM\Column(length: 255, nullable: true)]
    private string $characteristics = "";

    #[ORM\Column(length: 255, nullable: true)]
    private string $utility = "";

    #[ORM\Column(length: 255, nullable: true)]
    private string $cultivationCondition = "";

    #[ORM\Column(type: Types::ARRAY)]
    private array $images = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLatinName(): string
    {
        return $this->latinName;
    }

    public function setLatinName(string $latinName): static
    {
        $this->latinName = $latinName;

        return $this;
    }

    public function getCommonName(): string
    {
        return $this->commonName;
    }

    public function setCommonName(string $commonName): static
    {
        $this->commonName = $commonName;

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

    public function getOrigin(): string
    {
        return $this->origin;
    }

    public function setOrigin(string $origin): static
    {
        $this->origin = $origin;

        return $this;
    }

    public function getCharacteristics(): string
    {
        return $this->characteristics;
    }

    public function setCharacteristics(string $characteristics): static
    {
        $this->characteristics = $characteristics;

        return $this;
    }

    public function getUtility(): string
    {
        return $this->utility;
    }

    public function setUtility(string $utility): static
    {
        $this->utility = $utility;

        return $this;
    }

    public function getCultivationCondition(): string
    {
        return $this->cultivationCondition;
    }

    public function setCultivationCondition(string $cultivationCondition): static
    {
        $this->cultivationCondition = $cultivationCondition;

        return $this;
    }

    public function getImages(): array
    {
        return $this->images;
    }

    public function setImages(array $images): static
    {
        $this->images = $images;

        return $this;
    }
}
