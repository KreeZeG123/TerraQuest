<?php

namespace App\Entity;

use App\Repository\OngoingChallengeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OngoingChallengeRepository::class)]
class OngoingChallenge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Challenge $challenge = null;

    #[ORM\Column(length: 255)]
    private ?string $lastHint = null;

    #[ORM\Column(length: 255)]
    private ?string $lastScannedSpecies = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChallenge(): ?Challenge
    {
        return $this->challenge;
    }

    public function setChallenge(?Challenge $challenge): static
    {
        $this->challenge = $challenge;

        return $this;
    }

    public function getLastHint(): ?string
    {
        return $this->lastHint;
    }

    public function setLastHint(string $lastHint): static
    {
        $this->lastHint = $lastHint;

        return $this;
    }

    public function getLastScannedSpecies(): ?string
    {
        return $this->lastScannedSpecies;
    }

    public function setLastScannedSpecies(string $lastScannedSpecies): static
    {
        $this->lastScannedSpecies = $lastScannedSpecies;

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
}
