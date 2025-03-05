<?php

namespace App\DTO;

class ScannedSpeciesDTO
{

    public function __construct(
        private readonly int $id,
        private readonly string $latinName
    )
    {

    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLatinName(): string
    {
        return $this->latinName;
    }

}