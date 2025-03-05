<?php

namespace App\DTO;

class OngoingChallengeDTO
{

    public function __construct(
        private readonly int $numberOfAreasCompleted,
        private readonly int $numberOfAreas,
        private readonly string $type,
        private readonly string $description,
        private readonly string $image,
        private readonly string $lastHint,
        private readonly string $lastScannedSpecies
    )
    {

    }

    public function getNumberOfAreasCompleted(): int
    {
        return $this->numberOfAreasCompleted;
    }

    public function getNumberOfAreas(): int
    {
        return $this->numberOfAreas;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getLastHint(): string
    {
        return $this->lastHint;
    }

    public function getLastScannedSpecies(): string
    {
        return $this->lastScannedSpecies;
    }

}