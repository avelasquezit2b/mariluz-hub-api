<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OccupancyTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OccupancyTypeRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["occupancyTypeReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "occupancyType"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class OccupancyType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['occupancyTypeReduced', 'occupancyType'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['occupancyTypeReduced', 'occupancyType'])]
    private ?int $totalCapacity = null;

    #[ORM\Column]
    #[Groups(['occupancyTypeReduced', 'occupancyType'])]
    private ?int $minAdultCapacity = null;

    #[ORM\Column]
    #[Groups(['occupancyTypeReduced', 'occupancyType'])]
    private ?int $maxAdultCapacity = null;

    #[ORM\Column]
    #[Groups(['occupancyTypeReduced', 'occupancyType'])]
    private ?int $minKidsCapacity = null;

    #[ORM\Column]
    #[Groups(['occupancyTypeReduced', 'occupancyType'])]
    private ?int $maxKidsCapacity = null;

    #[ORM\Column(length: 25)]
    #[Groups(['occupancyTypeReduced', 'occupancyType','roomTypeReduced'])]
    private ?string $code = null;

    #[ORM\OneToMany(mappedBy: 'occupancyType', targetEntity: RoomType::class)]
    private Collection $roomTypes;

    #[ORM\ManyToMany(targetEntity: HotelFee::class, mappedBy: 'OccupancyType')]
    private Collection $hotelFees;

    public function __construct()
    {
        $this->roomTypes = new ArrayCollection();
        $this->hotelFees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalCapacity(): ?int
    {
        return $this->totalCapacity;
    }

    public function setTotalCapacity(int $totalCapacity): static
    {
        $this->totalCapacity = $totalCapacity;

        return $this;
    }

    public function getMinAdultCapacity(): ?int
    {
        return $this->minAdultCapacity;
    }

    public function setMinAdultCapacity(int $minAdultCapacity): static
    {
        $this->minAdultCapacity = $minAdultCapacity;

        return $this;
    }

    public function getMaxAdultCapacity(): ?int
    {
        return $this->maxAdultCapacity;
    }

    public function setMaxAdultCapacity(int $maxAdultCapacity): static
    {
        $this->maxAdultCapacity = $maxAdultCapacity;

        return $this;
    }

    public function getMinKidsCapacity(): ?int
    {
        return $this->minKidsCapacity;
    }

    public function setMinKidsCapacity(int $minKidsCapacity): static
    {
        $this->minKidsCapacity = $minKidsCapacity;

        return $this;
    }

    public function getMaxKidsCapacity(): ?int
    {
        return $this->maxKidsCapacity;
    }

    public function setMaxKidsCapacity(int $maxKidsCapacity): static
    {
        $this->maxKidsCapacity = $maxKidsCapacity;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection<int, RoomType>
     */
    public function getRoomTypes(): Collection
    {
        return $this->roomTypes;
    }

    public function addRoomType(RoomType $roomType): static
    {
        if (!$this->roomTypes->contains($roomType)) {
            $this->roomTypes->add($roomType);
            $roomType->setOccupancyType($this);
        }

        return $this;
    }

    public function removeRoomType(RoomType $roomType): static
    {
        if ($this->roomTypes->removeElement($roomType)) {
            // set the owning side to null (unless already changed)
            if ($roomType->getOccupancyType() === $this) {
                $roomType->setOccupancyType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, HotelFee>
     */
    public function getHotelFees(): Collection
    {
        return $this->hotelFees;
    }

    public function addHotelFee(HotelFee $hotelFee): static
    {
        if (!$this->hotelFees->contains($hotelFee)) {
            $this->hotelFees->add($hotelFee);
            $hotelFee->addOccupancyType($this);
        }

        return $this;
    }

    public function removeHotelFee(HotelFee $hotelFee): static
    {
        if ($this->hotelFees->removeElement($hotelFee)) {
            $hotelFee->removeOccupancyType($this);
        }

        return $this;
    }
}
