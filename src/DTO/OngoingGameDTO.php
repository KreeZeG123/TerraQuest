<?php

namespace App\DTO;

class OngoingGameDTO
{

    public function __construct(
        private readonly string $title,
        private readonly int $gameID
    )
    {

    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getGameID(): int
    {
        return $this->gameID;
    }

}