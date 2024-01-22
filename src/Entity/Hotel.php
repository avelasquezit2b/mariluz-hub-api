<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\HotelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HotelRepository::class)]
#[ApiResource]
class Hotel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'hotels')]
    private ?Supplier $supplier = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $rating = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $checkIn = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $checkOut = null;

    #[ORM\ManyToOne(inversedBy: 'hotels')]
    private ?Location $location = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $latitude = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $longitude = null;

    #[ORM\ManyToMany(targetEntity: Zone::class, inversedBy: 'hotels')]
    private Collection $zones;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $shortDescription = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $extendedDescription = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $highlights = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $importantInformation = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $cancelationConditions = null;

    #[ORM\OneToMany(mappedBy: 'hotel', targetEntity: RoomType::class)]
    #[ApiSubresource]
    private Collection $roomTypes;

    #[ORM\Column(nullable: true)]
    private ?bool $isActive = null;

    #[ORM\OneToOne(inversedBy: 'hotel', cascade: ['persist', 'remove'])]
    #[ApiSubresource]
    private ?HotelServices $services = null;

    public function __construct()
    {
        $this->zones = new ArrayCollection();
        $this->roomTypes = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getSupplier(): ?Supplier
    {
        return $this->supplier;
    }

    public function setSupplier(?Supplier $supplier): static
    {
        $this->supplier = $supplier;

        return $this;
    }

    public function getRating(): ?string
    {
        return $this->rating;
    }

    public function setRating(?string $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getCheckIn(): ?string
    {
        return $this->checkIn;
    }

    public function setCheckIn(?string $checkIn): static
    {
        $this->checkIn = $checkIn;

        return $this;
    }

    public function getCheckOut(): ?string
    {
        return $this->checkOut;
    }

    public function setCheckOut(?string $checkOut): static
    {
        $this->checkOut = $checkOut;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection<int, Zone>
     */
    public function getZones(): Collection
    {
        return $this->zones;
    }

    public function addZone(Zone $zone): static
    {
        if (!$this->zones->contains($zone)) {
            $this->zones->add($zone);
        }

        return $this;
    }

    public function removeZone(Zone $zone): static
    {
        $this->zones->removeElement($zone);

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(?string $shortDescription): static
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getExtendedDescription(): ?string
    {
        return $this->extendedDescription;
    }

    public function setExtendedDescription(?string $extendedDescription): static
    {
        $this->extendedDescription = $extendedDescription;

        return $this;
    }

    public function getHighlights(): ?string
    {
        return $this->highlights;
    }

    public function setHighlights(?string $highlights): static
    {
        $this->highlights = $highlights;

        return $this;
    }

    public function getImportantInformation(): ?string
    {
        return $this->importantInformation;
    }

    public function setImportantInformation(?string $importantInformation): static
    {
        $this->importantInformation = $importantInformation;

        return $this;
    }

    public function getCancelationConditions(): ?string
    {
        return $this->cancelationConditions;
    }

    public function setCancelationConditions(?string $cancelationConditions): static
    {
        $this->cancelationConditions = $cancelationConditions;

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
            $roomType->setHotel($this);
        }

        return $this;
    }

    public function removeRoomType(RoomType $roomType): static
    {
        if ($this->roomTypes->removeElement($roomType)) {
            // set the owning side to null (unless already changed)
            if ($roomType->getHotel() === $this) {
                $roomType->setHotel(null);
            }
        }

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getServices(): ?HotelServices
    {
        return $this->services;
    }

    public function setServices(?HotelServices $services): static
    {
        $this->services = $services;

        return $this;
    }
}
