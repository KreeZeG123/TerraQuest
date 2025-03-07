<?php

namespace App\Entity;

use App\Repository\BadgeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BadgeRepository::class)]
class Badge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    private ?string $legend = null;

    #[ORM\Column(length: 255)]
    private ?string $unlockingCondition = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getLegend(): ?string
    {
        return $this->legend;
    }

    public function setLegend(string $legend): static
    {
        $this->legend = $legend;

        return $this;
    }

    public function getUnlockingCondition(): ?string
    {
        return $this->unlockingCondition;
    }

    public function setUnlockingCondition(string $unlockingCondition): static
    {
        $this->unlockingCondition = $unlockingCondition;

        return $this;
    }

    public function isUnlocked(Statistics $stats): bool
    {
        return $this->evaluateWin($stats->getWins(), $this->getUnlockingCondition());
    }

    public function evaluateWin(mixed $valueToCompare, String $conditions)
    {
        // Expression régulière pour capturer les éléments du message
        $pattern = '/(win)\s*(>=|<=|<|>|=)\s*(\d+)/i';

        // Vérifier si le message correspond au format attendu
        if (preg_match($pattern, $conditions, $matches)) {
            // Récupérer l'opérateur et le nombre depuis le message
            $operator = $matches[2];
            $number = (int)$matches[3];

            // Comparaison en fonction de l'opérateur
            switch ($operator) {
                case '>':
                    return $valueToCompare > $number;
                case '<':
                    return $valueToCompare < $number;
                case '>=':
                    return $valueToCompare >= $number;
                case '<=':
                    return $valueToCompare <= $number;
                case '=':
                    return $valueToCompare == $number;
                default:
                    return false;
            }
        } else {
            // Si le message n'est pas valide
            return false;
        }

    }



}
