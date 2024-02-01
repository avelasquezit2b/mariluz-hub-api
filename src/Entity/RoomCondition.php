<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RoomConditionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RoomConditionRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["roomConditionReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "roomCondition"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class RoomCondition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['roomConditionReduced', 'roomCondition', 'hotel'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'roomConditions')]
    #[Groups(['roomConditionReduced', 'roomCondition'])]
    private ?RoomType $roomType = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['roomConditionReduced', 'roomCondition', 'hotel'])]
    private ?int $quota = null;

    #[ORM\ManyToOne(inversedBy: 'roomConditions')]
    #[Groups(['roomConditionReduced', 'roomCondition'])]
    private ?HotelCondition $hotelCondition = null;

    #[ORM\OneToMany(mappedBy: 'roomCondition', targetEntity: PensionTypePrice::class, cascade: ['remove'])]
    #[Groups(['roomConditionReduced', 'roomCondition', 'hotel'])]
    private Collection $pensionTypePrices;

    #[ORM\Column(nullable: true)]
    #[Groups(['roomConditionReduced', 'roomCondition', 'hotel'])]
    private ?int $minStay = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['roomConditionReduced', 'roomCondition', 'hotel'])]
    private ?string $individualSupplement = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    #[Groups(['roomConditionReduced', 'roomCondition', 'hotel'])]
    private ?array $extras = null;

    #[ORM\OneToMany(mappedBy: 'roomCondition', targetEntity: RoomDiscount::class, cascade: ['remove'])]
    #[Groups(['roomConditionReduced', 'roomCondition', 'hotel'])]
    private Collection $roomDiscounts;

    public function __construct()
    {
        $this->pensionTypePrices = new ArrayCollection();
        $this->roomDiscounts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoomType(): ?RoomType
    {
        return $this->roomType;
    }

    public function setRoomType(?RoomType $roomType): static
    {
        $this->roomType = $roomType;

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

    public function getHotelCondition(): ?HotelCondition
    {
        return $this->hotelCondition;
    }

    public function setHotelCondition(?HotelCondition $hotelCondition): static
    {
        $this->hotelCondition = $hotelCondition;

        return $this;
    }

    /**
     * @return Collection<int, PensionTypePrice>
     */
    public function getPensionTypePrices(): Collection
    {
        return $this->pensionTypePrices;
    }

    public function addPensionTypePrice(PensionTypePrice $pensionTypePrice): static
    {
        if (!$this->pensionTypePrices->contains($pensionTypePrice)) {
            $this->pensionTypePrices->add($pensionTypePrice);
            $pensionTypePrice->setRoomCondition($this);
        }

        return $this;
    }

    public function removePensionTypePrice(PensionTypePrice $pensionTypePrice): static
    {
        if ($this->pensionTypePrices->removeElement($pensionTypePrice)) {
            // set the owning side to null (unless already changed)
            if ($pensionTypePrice->getRoomCondition() === $this) {
                $pensionTypePrice->setRoomCondition(null);
            }
        }

        return $this;
    }

    public function getMinStay(): ?int
    {
        return $this->minStay;
    }

    public function setMinStay(?int $minStay): static
    {
        $this->minStay = $minStay;

        return $this;
    }

    public function getIndividualSupplement(): ?string
    {
        return $this->individualSupplement;
    }

    public function setIndividualSupplement(?string $individualSupplement): static
    {
        $this->individualSupplement = $individualSupplement;

        return $this;
    }

    public function getExtras(): ?array
    {
        return $this->extras;
    }

    public function setExtras(?array $extras): static
    {
        $this->extras = $extras;

        return $this;
    }

    /**
     * @return Collection<int, RoomDiscount>
     */
    public function getRoomDiscounts(): Collection
    {
        return $this->roomDiscounts;
    }

    public function addRoomDiscount(RoomDiscount $roomDiscount): static
    {
        if (!$this->roomDiscounts->contains($roomDiscount)) {
            $this->roomDiscounts->add($roomDiscount);
            $roomDiscount->setRoomCondition($this);
        }

        return $this;
    }

    public function removeRoomDiscount(RoomDiscount $roomDiscount): static
    {
        if ($this->roomDiscounts->removeElement($roomDiscount)) {
            // set the owning side to null (unless already changed)
            if ($roomDiscount->getRoomCondition() === $this) {
                $roomDiscount->setRoomCondition(null);
            }
        }

        return $this;
    }
}
