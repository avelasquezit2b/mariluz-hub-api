<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RoomDiscountRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RoomDiscountRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["roomDiscountReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "roomDiscount"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class RoomDiscount
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['roomDiscountReduced', 'roomDiscount', 'hotelFeeReduced'])]
    private ?int $id = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['roomDiscountReduced', 'roomDiscount', 'hotelFeeReduced', 'hotelAvailability'])]
    private ?string $type = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['roomDiscountReduced', 'roomDiscount', 'hotelFeeReduced', 'hotelAvailability'])]
    private ?int $ageFrom = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['roomDiscountReduced', 'roomDiscount', 'hotelFeeReduced', 'hotelAvailability'])]
    private ?int $ageTo = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['roomDiscountReduced', 'roomDiscount', 'hotelFeeReduced', 'hotelAvailability'])]
    private ?string $discount = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['roomDiscountReduced', 'roomDiscount', 'hotelFeeReduced', 'hotelAvailability'])]
    private ?int $number = null;

    #[ORM\ManyToOne(inversedBy: 'roomDiscounts')]
    #[Groups(['roomDiscountReduced', 'roomDiscount'])]
    private ?RoomCondition $roomCondition = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getAgeFrom(): ?int
    {
        return $this->ageFrom;
    }

    public function setAgeFrom(?int $ageFrom): static
    {
        $this->ageFrom = $ageFrom;

        return $this;
    }

    public function getAgeTo(): ?int
    {
        return $this->ageTo;
    }

    public function setAgeTo(?int $ageTo): static
    {
        $this->ageTo = $ageTo;

        return $this;
    }

    public function getDiscount(): ?string
    {
        return $this->discount;
    }

    public function setDiscount(?string $discount): static
    {
        $this->discount = $discount;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): static
    {
        $this->number = $number;

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
}
