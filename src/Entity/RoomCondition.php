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
    #[Groups(['roomConditionReduced', 'roomCondition', 'hotelFeeReduced'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'roomConditions')]
    #[Groups(['roomConditionReduced', 'roomCondition', 'hotelFeeReduced', 'hotelAvailability', 'hotelAvailabilityReduced'])]
    private ?RoomType $roomType = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['roomConditionReduced', 'roomCondition', 'hotelFeeReduced'])]
    private ?int $quota = null;

    #[ORM\OneToMany(mappedBy: 'roomCondition', targetEntity: PensionTypePrice::class, cascade: ['remove'])]
    #[Groups(['roomConditionReduced', 'roomCondition', 'hotelFeeReduced', 'hotelAvailability'])]
    private Collection $pensionTypePrices;

    #[ORM\Column(nullable: true)]
    #[Groups(['roomConditionReduced', 'roomCondition', 'hotelFeeReduced', 'hotelAvailability'])]
    private ?int $minStay = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['roomConditionReduced', 'roomCondition', 'hotelFeeReduced', 'hotelAvailability'])]
    private ?string $individualSupplement = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    #[Groups(['roomConditionReduced', 'roomCondition', 'hotelFeeReduced', 'hotelAvailability'])]
    private ?array $extras = null;

    #[ORM\OneToMany(mappedBy: 'roomCondition', targetEntity: RoomDiscount::class, cascade: ['remove'])]
    #[Groups(['roomConditionReduced', 'roomCondition', 'hotelFeeReduced', 'hotelAvailability'])]
    private Collection $roomDiscounts;

    #[ORM\ManyToOne(inversedBy: 'roomConditions')]
    #[Groups(['roomConditionReduced', 'roomCondition', 'hotelFeeReduced', 'hotelAvailabilityReduced'])]
    private ?HotelSeason $hotelSeason = null;

    #[ORM\OneToMany(mappedBy: 'roomCondition', targetEntity: HotelAvailability::class, cascade: ['remove'])]
    private Collection $hotelAvailabilities;

    #[ORM\Column(nullable: true)]
    #[Groups(['roomConditionReduced', 'roomCondition', 'hotelFeeReduced', 'hotelAvailability'])]
    private ?int $minNightsSupplement = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['roomConditionReduced', 'roomCondition', 'hotelFeeReduced', 'hotelAvailability'])]
    private ?string $nightsSupplement = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $supplementType = null;

    public function __construct()
    {
        $this->pensionTypePrices = new ArrayCollection();
        $this->roomDiscounts = new ArrayCollection();
        $this->hotelAvailabilities = new ArrayCollection();
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

    public function getHotelSeason(): ?HotelSeason
    {
        return $this->hotelSeason;
    }

    public function setHotelSeason(?HotelSeason $hotelSeason): static
    {
        $this->hotelSeason = $hotelSeason;

        return $this;
    }

    /**
     * @return Collection<int, HotelAvailability>
     */
    public function getHotelAvailabilities(): Collection
    {
        return $this->hotelAvailabilities;
    }

    public function addHotelAvailability(HotelAvailability $hotelAvailability): static
    {
        if (!$this->hotelAvailabilities->contains($hotelAvailability)) {
            $this->hotelAvailabilities->add($hotelAvailability);
            $hotelAvailability->setRoomCondition($this);
        }

        return $this;
    }

    public function removeHotelAvailability(HotelAvailability $hotelAvailability): static
    {
        if ($this->hotelAvailabilities->removeElement($hotelAvailability)) {
            // set the owning side to null (unless already changed)
            if ($hotelAvailability->getRoomCondition() === $this) {
                $hotelAvailability->setRoomCondition(null);
            }
        }

        return $this;
    }

    public function getMinNightsSupplement(): ?int
    {
        return $this->minNightsSupplement;
    }

    public function setMinNightsSupplement(?int $minNightsSupplement): static
    {
        $this->minNightsSupplement = $minNightsSupplement;

        return $this;
    }

    public function getNightsSupplement(): ?string
    {
        return $this->nightsSupplement;
    }

    public function setNightsSupplement(?string $nightsSupplement): static
    {
        $this->nightsSupplement = $nightsSupplement;

        return $this;
    }

    public function getSupplementType(): ?string
    {
        return $this->supplementType;
    }

    public function setSupplementType(?string $supplementType): static
    {
        $this->supplementType = $supplementType;

        return $this;
    }
}
