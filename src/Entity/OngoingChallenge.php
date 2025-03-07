<?php

namespace App\Entity;

use App\Repository\OngoingChallengeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\Column]
    private int $lastHint = 0;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToOne(mappedBy: 'ongoingChallenge', cascade: ['persist', 'remove'])]
    private ?Game $game = null;

    /**
     * @var Collection<int, Species>
     */
    #[ORM\ManyToMany(targetEntity: Species::class)]
    private Collection $scannedSpecies;

    public function __construct()
    {
        $this->scannedSpecies = new ArrayCollection();
    }


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

    public function getLastHint(): int
    {
        return $this->lastHint;
    }

    public function setLastHint(int $lastHint): static
    {
        $this->lastHint = $lastHint;

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

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(Game $game): static
    {
        // set the owning side of the relation if necessary
        if ($game->getOngoingChallenge() !== $this) {
            $game->setOngoingChallenge($this);
        }

        $this->game = $game;

        return $this;
    }

    /**
     * @return Collection<int, Species>
     */
    public function getScannedSpecies(): Collection
    {
        return $this->scannedSpecies;
    }

    public function addScannedSpecies(Species $scannedSpecies): static
    {
        if (!$this->scannedSpecies->contains($scannedSpecies)) {
            $this->scannedSpecies->add($scannedSpecies);
        }

        return $this;
    }

    public function removeScannedSpecies(Species $scannedSpecies): static
    {
        $this->scannedSpecies->removeElement($scannedSpecies);

        return $this;
    }

    public function clearScannedSpecies(): static
    {
        $this->scannedSpecies->clear();

        return $this;
    }

    public function getLastHintTxt() : string
    {
        $hintIdx = $this->getLastHint() - 1;

        if ($hintIdx < 0) {
            return "Aucun indice demandé";
        }

        $hint = $this->getChallenge()->getHints();
        $maxIdx = count($hint) - 1;
        if($hintIdx >  $maxIdx) {
            $hintIdx = $maxIdx;
        }

        return $hint[$hintIdx];
    }

    public function areHintAvaible(int $numberOfHint = null): bool
    {
        if ($numberOfHint === null) {
            $numberOfHint = $this->getLastHint();
        }

        $maxHint = count($this->getChallenge()->getHints());
        return $numberOfHint < $maxHint;
    }

    public function newHint(): bool
    {
        $newLastHint = $this->getLastHint() + 1;
        $maxHint = count($this->getChallenge()->getHints());

        if( !$this->areHintAvaible($newLastHint) ) {
            $this->setLastHint($maxHint);
            return false;
        }

        $this->setLastHint($newLastHint);
        return true;
    }
}
