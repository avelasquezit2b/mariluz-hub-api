<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ActivityPriceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityPriceRepository::class)]
#[ApiResource]
class ActivityPrice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $price = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $cost = null;

    #[ORM\Column(nullable: true)]
    private ?int $quota = null;

    #[ORM\ManyToOne(inversedBy: 'activityPrices')]
    private ?ClientType $clientType = null;

    #[ORM\ManyToOne(inversedBy: 'activityPrices')]
    private ?ActivitySchedule $activitySchedule = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCost(): ?string
    {
        return $this->cost;
    }

    public function setCost(?string $cost): static
    {
        $this->cost = $cost;

        return $this;
    }

    public function getQuota(): ?int
    {
        return $this->quota;
    }

    public function setQuota(?int $quota): static
    {
        $this->quota = $quota;

        return $this;
    }

    public function getClientType(): ?ClientType
    {
        return $this->clientType;
    }

    public function setClientType(?ClientType $clientType): static
    {
        $this->clientType = $clientType;

        return $this;
    }

    public function getActivitySchedule(): ?ActivitySchedule
    {
        return $this->activitySchedule;
    }

    public function setActivitySchedule(?ActivitySchedule $activitySchedule): static
    {
        $this->activitySchedule = $activitySchedule;

        return $this;
    }
}
