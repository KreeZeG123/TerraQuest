<?php

namespace App\DTO;

class HotspotDTO
{
    public function __construct(
        private readonly float $lat,
        private readonly float $lng,
        private readonly ?string $title = null,
        private readonly ?int $areaID = null,
        private readonly ?string $slug = null,
        private readonly ?string $link = null
    )
    {

    }

    public function getAreaID(): ?int
    {
        return $this->areaID;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getLat(): float
    {
        return $this->lat;
    }

    public function getLng(): float
    {
        return $this->lng;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

}