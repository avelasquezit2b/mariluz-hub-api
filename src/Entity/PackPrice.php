<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PackPriceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PackPriceRepository::class)]
#[ApiResource]
class PackPrice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'packPrices')]
    private ?Hotel $hotel = null;

    #[ORM\ManyToOne(inversedBy: 'packPrices')]
    private ?Activity $activity = null;

    #[ORM\ManyToOne(inversedBy: 'packPrices')]
    private ?Extra $extra = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $markup = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $commission = null;

    #[ORM\ManyToOne(inversedBy: 'packPrices')]
    private ?PackSeason $packSeason = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHotel(): ?Hotel
    {
        return $this->hotel;
    }

    public function setHotel(?Hotel $hotel): static
    {
        $this->hotel = $hotel;

        return $this;
    }

    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    public function setActivity(?Activity $activity): static
    {
        $this->activity = $activity;

        return $this;
    }

    public function getExtra(): ?Extra
    {
        return $this->extra;
    }

    public function setExtra(?Extra $extra): static
    {
        $this->extra = $extra;

        return $this;
    }

    public function getMarkup(): ?string
    {
        return $this->markup;
    }

    public function setMarkup(?string $markup): static
    {
        $this->markup = $markup;

        return $this;
    }

    public function getCommission(): ?string
    {
        return $this->commission;
    }

    public function setCommission(?string $commission): static
    {
        $this->commission = $commission;

        return $this;
    }

    public function getPackSeason(): ?PackSeason
    {
        return $this->packSeason;
    }

    public function setPackSeason(?PackSeason $packSeason): static
    {
        $this->packSeason = $packSeason;

        return $this;
    }
}
