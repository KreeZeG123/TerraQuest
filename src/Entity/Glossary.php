<?php

namespace App\Entity;

use App\Repository\GlossaryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GlossaryRepository::class)]
class Glossary
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(mappedBy: 'glossary', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setGlossary(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getGlossary() !== $this) {
            $user->setGlossary($this);
        }

        $this->user = $user;

        return $this;
    }
}
