<?php

namespace App\DTO;

class GalleryDTO
{
    public function __construct(
        private readonly string $image,
        private readonly string $title,
        private readonly string $legend,
        private readonly bool $unlocked,
    )
    {

    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getLegend(): string
    {
        return $this->legend;
    }

    public function isUnlocked(): bool
    {
        return $this->unlocked;
    }

    public function getImage(): string
    {
        return $this->image;
    }
}