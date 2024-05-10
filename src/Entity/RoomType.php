<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RoomTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RoomTypeRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["roomTypeReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "roomType"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class RoomType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['roomTypeReduced', 'roomType', 'hotel', 'hotelFeeReduced'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
#[Groups(['roomTypeReduced', 'roomType', 'hotel', 'hotelAvailability','hotelAvailabilityReduced', 'hotelFeeReduced'])]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['roomTypeReduced', 'roomType', 'hotel', 'hotelAvailability'])]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'roomTypes')]
    #[Groups(['roomTypeReduced', 'roomType'])]
    private ?Hotel $hotel = null;

    #[ORM\ManyToOne(inversedBy: 'roomTypes')]
    #[Groups(['roomTypeReduced', 'roomType', 'hotel'])]
    private ?OccupancyType $occupancyType = null;

    #[ORM\OneToMany(mappedBy: 'roomType', targetEntity: RoomCondition::class, cascade: ['remove'])]
    // #[Groups(['roomTypeReduced', 'roomType', 'hotel'])]
    private Collection $roomConditions;

    #[ORM\OneToMany(mappedBy: 'roomType', targetEntity: MediaObject::class, cascade: ['remove'])]
    #[Groups(['roomTypeReduced', 'roomType', 'hotel', 'hotelAvailability'])]
    #[ORM\OrderBy(["position" => "ASC"])]
    private Collection $media;

    #[ORM\Column(nullable: true)]
    #[Groups(['roomTypeReduced', 'roomType', 'hotel', 'hotelAvailability'])]
    private ?int $minAdultsCapacity = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['roomTypeReduced', 'roomType', 'hotel', 'hotelAvailability', 'hotelFeeReduced'])]
    private ?int $maxAdultsCapacity = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['roomTypeReduced', 'roomType', 'hotel', 'hotelAvailability'])]
    private ?int $minKidsCapacity = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['roomTypeReduced', 'roomType', 'hotel', 'hotelAvailability', 'hotelFeeReduced'])]
    private ?int $maxKidsCapacity = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['roomTypeReduced', 'roomType', 'hotel', 'hotelAvailability'])]
    private ?int $totalCapacity = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['hotel'])]
    private ?string $price = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['roomTypeReduced', 'roomType', 'hotel', 'hotelAvailability'])]
    private ?int $minBabiesCapacity = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['roomTypeReduced', 'roomType', 'hotel', 'hotelAvailability', 'hotelFeeReduced'])]
    private ?int $maxBabiesCapacity = null;

    public function __construct()
    {
        $this->roomConditions = new ArrayCollection();
        $this->media = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
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

    public function getOccupancyType(): ?OccupancyType
    {
        return $this->occupancyType;
    }

    public function setOccupancyType(?OccupancyType $occupancyType): static
    {
        $this->occupancyType = $occupancyType;

        return $this;
    }

    /**
     * @return Collection<int, RoomCondition>
     */
    public function getRoomConditions(): Collection
    {
        return $this->roomConditions;
    }

    public function addRoomCondition(RoomCondition $roomCondition): static
    {
        if (!$this->roomConditions->contains($roomCondition)) {
            $this->roomConditions->add($roomCondition);
            $roomCondition->setRoomType($this);
        }

        return $this;
    }

    public function removeRoomCondition(RoomCondition $roomCondition): static
    {
        if ($this->roomConditions->removeElement($roomCondition)) {
            // set the owning side to null (unless already changed)
            if ($roomCondition->getRoomType() === $this) {
                $roomCondition->setRoomType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MediaObject>
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(MediaObject $medium): static
    {
        if (!$this->media->contains($medium)) {
            $this->media->add($medium);
            $medium->setRoomType($this);
        }

        return $this;
    }

    public function removeMedium(MediaObject $medium): static
    {
        if ($this->media->removeElement($medium)) {
            // set the owning side to null (unless already changed)
            if ($medium->getRoomType() === $this) {
                $medium->setRoomType(null);
            }
        }

        return $this;
    }

    public function getMinAdultsCapacity(): ?int
    {
        return $this->minAdultsCapacity;
    }

    public function setMinAdultsCapacity(?int $minAdultsCapacity): static
    {
        $this->minAdultsCapacity = $minAdultsCapacity;

        return $this;
    }

    public function getMaxAdultsCapacity(): ?int
    {
        return $this->maxAdultsCapacity;
    }

    public function setMaxAdultsCapacity(?int $maxAdultsCapacity): static
    {
        $this->maxAdultsCapacity = $maxAdultsCapacity;

        return $this;
    }

    public function getMinKidsCapacity(): ?int
    {
        return $this->minKidsCapacity;
    }

    public function setMinKidsCapacity(?int $minKidsCapacity): static
    {
        $this->minKidsCapacity = $minKidsCapacity;

        return $this;
    }

    public function getMaxKidsCapacity(): ?int
    {
        return $this->maxKidsCapacity;
    }

    public function setMaxKidsCapacity(?int $maxKidsCapacity): static
    {
        $this->maxKidsCapacity = $maxKidsCapacity;

        return $this;
    }

    public function getTotalCapacity(): ?int
    {
        return $this->totalCapacity;
    }

    public function setTotalCapacity(?int $totalCapacity): static
    {
        $this->totalCapacity = $totalCapacity;

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

    public function getMinBabiesCapacity(): ?int
    {
        return $this->minBabiesCapacity;
    }

    public function setMinBabiesCapacity(?int $minBabiesCapacity): static
    {
        $this->minBabiesCapacity = $minBabiesCapacity;

        return $this;
    }

    public function getMaxBabiesCapacity(): ?int
    {
        return $this->maxBabiesCapacity;
    }

    public function setMaxBabiesCapacity(?int $maxBabiesCapacity): static
    {
        $this->maxBabiesCapacity = $maxBabiesCapacity;

        return $this;
    }
}
