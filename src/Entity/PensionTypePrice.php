<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PensionTypePriceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PensionTypePriceRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["pensionTypePricesReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "pensionTypePrices"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class PensionTypePrice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['pensionTypePricesReduced', 'pensionTypePrices', 'hotelFeeReduced', 'hotelAvailability'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'pensionTypePrices')]
    #[Groups(['pensionTypePricesReduced', 'pensionTypePrices', 'hotelFeeReduced', 'hotelAvailability'])]
    private ?PensionType $pensionType = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['pensionTypePricesReduced', 'pensionTypePrices', 'hotelFeeReduced', 'hotelAvailability'])]
    private ?string $price = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['pensionTypePricesReduced', 'pensionTypePrices', 'hotelFeeReduced', 'hotelAvailability'])]
    private ?string $cost = null;

    #[ORM\ManyToOne(inversedBy: 'pensionTypePrices')]
    #[Groups(['pensionTypePricesReduced', 'pensionTypePrices'])]
    private ?RoomCondition $roomCondition = null;

    #[ORM\ManyToOne(inversedBy: 'pensionTypePrices')]
    #[Groups(['pensionTypePricesReduced', 'pensionTypePrices', 'hotelFeeReduced', 'hotelAvailability'])]
    private ?CancellationType $cancellationType = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['pensionTypePricesReduced', 'pensionTypePrices', 'hotelFeeReduced'])]
    private ?string $commission = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPensionType(): ?PensionType
    {
        return $this->pensionType;
    }

    public function setPensionType(?PensionType $pensionType): static
    {
        $this->pensionType = $pensionType;

        return $this;
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

    public function getRoomCondition(): ?RoomCondition
    {
        return $this->roomCondition;
    }

    public function setRoomCondition(?RoomCondition $roomCondition): static
    {
        $this->roomCondition = $roomCondition;

        return $this;
    }

    public function getCancellationType(): ?CancellationType
    {
        return $this->cancellationType;
    }

    public function setCancellationType(?CancellationType $cancellationType): static
    {
        $this->cancellationType = $cancellationType;

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
}
