<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Journey $journey = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $completedAreas = [];

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    private ?User $user = null;

    #[ORM\OneToOne(inversedBy: 'game', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?OngoingChallenge $ongoingChallenge = null;

    #[ORM\Column]
    private bool $isFinished = false;

    #[ORM\Column]
    private ?int $numberOfAreasCompleted = null;

    #[ORM\Column]
    private ?int $numberOfAreas = null;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJourney(): ?Journey
    {
        return $this->journey;
    }

    public function setJourney(?Journey $journey): static
    {
        $this->journey = $journey;

        return $this;
    }

    public function getCompletedAreas(): array
    {
        return $this->completedAreas;
    }

    public function setCompletedAreas(array $completedAreas): static
    {
        $this->completedAreas = $completedAreas;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function isFinished(): bool
    {
        return $this->isFinished;
    }

    public function setIsFinished(bool $isFinished): static
    {
        $this->isFinished = $isFinished;

        return $this;
    }

    public function getNumberOfAreasCompleted(): ?int
    {
        return $this->numberOfAreasCompleted;
    }

    public function setNumberOfAreasCompleted(int $numberOfAreasCompleted): static
    {
        $this->numberOfAreasCompleted = $numberOfAreasCompleted;

        return $this;
    }

    public function getNumberOfAreas(): ?int
    {
        return $this->numberOfAreas;
    }

    public function setNumberOfAreas(int $numberOfAreas): static
    {
        $this->numberOfAreas = $numberOfAreas;

        return $this;
    }

    public function getOngoingChallenge(): ?OngoingChallenge
    {
        return $this->ongoingChallenge;
    }

    public function setOngoingChallenge(OngoingChallenge $ongoingChallenge): static
    {
        $this->ongoingChallenge = $ongoingChallenge;

        return $this;
    }
}
