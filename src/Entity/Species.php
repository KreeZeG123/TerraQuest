<?php

namespace App\Entity;

use App\Repository\SpeciesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SpeciesRepository::class)]
#[UniqueEntity('latinName')]
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

    #[ORM\Column(length: 2047, nullable: true)]
    private string $origin = "";

    #[ORM\Column(length: 4095, nullable: true)]
    private string $characteristics = "";

    #[ORM\Column(length: 2047, nullable: true)]
    private string $utility = "";

    #[ORM\Column(length: 4095, nullable: true)]
    private string $cultivationCondition = "";

    #[ORM\Column(type: Types::ARRAY)]
    private array $images = [];

    #[ORM\Column]
    private ?float $latGPS = null;

    #[ORM\Column]
    private ?float $lngGPS = null;

    #[ORM\ManyToOne(inversedBy: 'species')]
    private ?Area $area = null;

    /**
     * @var Collection<int, Quiz>
     */
    #[ORM\OneToMany(targetEntity: Quiz::class, mappedBy: 'species', orphanRemoval: true)]
    private Collection $quizzes;

    public function __construct()
    {
        $this->quizzes = new ArrayCollection();
    }

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

    /**
     * @param int|null $id
     */
    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getLatGPS(): ?float
    {
        return $this->latGPS;
    }

    public function setLatGPS(float $latGPS): static
    {
        $this->latGPS = $latGPS;

        return $this;
    }

    public function getLngGPS(): ?float
    {
        return $this->lngGPS;
    }

    public function setLngGPS(float $lngGPS): static
    {
        $this->lngGPS = $lngGPS;

        return $this;
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
     * @return Collection<int, Quiz>
     */
    public function getQuizzes(): Collection
    {
        return $this->quizzes;
    }

    public function addQuiz(Quiz $quiz): static
    {
        if (!$this->quizzes->contains($quiz)) {
            $this->quizzes->add($quiz);
            $quiz->setSpecies($this);
        }

        return $this;
    }

    public function removeQuiz(Quiz $quiz): static
    {
        if ($this->quizzes->removeElement($quiz)) {
            // set the owning side to null (unless already changed)
            if ($quiz->getSpecies() === $this) {
                $quiz->setSpecies(null);
            }
        }

        return $this;
    }
}
