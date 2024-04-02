<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ActivityPriceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ActivityPriceRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["activityPriceReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "activityPrice"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class ActivityPrice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['activityPriceReduced', 'activityPrice', 'activityFeeReduced'])]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['activityPriceReduced', 'activityPrice', 'activityFeeReduced', 'activityAvailability'])]
    private ?string $price = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['activityPriceReduced', 'activityPrice', 'activityFeeReduced'])]
    private ?string $cost = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['activityPriceReduced', 'activityPrice', 'activityFeeReduced'])]
    private ?int $quota = null;

    #[ORM\ManyToOne(inversedBy: 'activityPrices')]
    #[Groups(['activityPriceReduced', 'activityPrice', 'activityFeeReduced', 'activityAvailability'])]
    private ?ClientType $clientType = null;

    #[ORM\ManyToOne(inversedBy: 'activityPrices')]
    #[Groups(['activityAvailabilityReduced'])]
    private ?ActivitySchedule $activitySchedule = null;

    public function __construct()
    {
        $this->activityAvailabilities = new ArrayCollection();
    }

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
