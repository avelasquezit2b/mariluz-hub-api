<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\HotelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HotelRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["hotelReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "hotel"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class Hotel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['hotelReduced', 'hotel'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['hotelReduced', 'hotel'])]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['hotelReduced', 'hotel'])]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'hotels')]
    #[Groups(['hotel'])]
    private ?Supplier $supplier = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['hotelReduced', 'hotel'])]
    private ?string $rating = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['hotel'])]
    private ?string $checkIn = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['hotel'])]
    private ?string $checkOut = null;

    #[ORM\ManyToOne(inversedBy: 'hotels')]
    #[Groups(['hotelReduced', 'hotel'])]
    private ?Location $location = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['hotelReduced', 'hotel'])]
    private ?string $address = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['hotel'])]
    private ?string $latitude = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['hotel'])]
    private ?string $longitude = null;

    #[ORM\ManyToMany(targetEntity: Zone::class, inversedBy: 'hotels')]
    #[Groups(['hotelReduced', 'hotel'])]
    private Collection $zones;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['hotelReduced', 'hotel'])]
    private ?string $shortDescription = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['hotel'])]
    private ?string $extendedDescription = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['hotel'])]
    private ?string $highlights = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['hotel'])]
    private ?string $importantInformation = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['hotel'])]
    private ?string $cancelationConditions = null;

    #[ORM\OneToMany(mappedBy: 'hotel', targetEntity: RoomType::class, cascade: ['remove'])]
    #[ApiSubresource]
    #[Groups(['hotel'])]
    private Collection $roomTypes;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotel'])]
    private ?bool $isActive = null;

    #[ORM\OneToOne(inversedBy: 'hotel', cascade: ['remove'])]
    #[Groups(['hotel'])]
    private ?HotelServices $services = null;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'relatedHotels')]
    #[Groups(['hotel'])]
    private Collection $relatedHotels;

    #[ORM\OneToMany(mappedBy: 'hotel', targetEntity: MediaObject::class, cascade: ['remove'])]
    #[Groups(['hotel'])]
    private Collection $media;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotel'])]
    private ?int $adultsFrom = null;

    #[ORM\OneToMany(mappedBy: 'hotel', targetEntity: HotelFee::class, cascade: ['remove'])]
    // #[Groups(['hotel'])]
    #[ApiSubresource]
    private Collection $hotelFees;

    #[ORM\OneToMany(mappedBy: 'hotel', targetEntity: HotelBooking::class)]
    private Collection $hotelBookings;

    public function __construct()
    {
        $this->zones = new ArrayCollection();
        $this->roomTypes = new ArrayCollection();
        $this->relatedHotels = new ArrayCollection();
        $this->media = new ArrayCollection();
        $this->hotelFees = new ArrayCollection();
        $this->hotelBookings = new ArrayCollection();
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

    /**
     * @return Collection<int, self>
     */
    public function getRelatedHotels(): Collection
    {
        return $this->relatedHotels;
    }

    public function addRelatedHotel(self $relatedHotel): static
    {
        if (!$this->relatedHotels->contains($relatedHotel)) {
            $this->relatedHotels->add($relatedHotel);
        }

        return $this;
    }

    public function removeRelatedHotel(self $relatedHotel): static
    {
        $this->relatedHotels->removeElement($relatedHotel);

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
            $medium->setHotel($this);
        }

        return $this;
    }

    public function removeMedium(MediaObject $medium): static
    {
        if ($this->media->removeElement($medium)) {
            // set the owning side to null (unless already changed)
            if ($medium->getHotel() === $this) {
                $medium->setHotel(null);
            }
        }

        return $this;
    }

    public function getAdultsFrom(): ?int
    {
        return $this->adultsFrom;
    }

    public function setAdultsFrom(?int $adultsFrom): static
    {
        $this->adultsFrom = $adultsFrom;

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
            $hotelFee->setHotel($this);
        }

        return $this;
    }

    public function removeHotelFee(HotelFee $hotelFee): static
    {
        if ($this->hotelFees->removeElement($hotelFee)) {
            // set the owning side to null (unless already changed)
            if ($hotelFee->getHotel() === $this) {
                $hotelFee->setHotel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, HotelBooking>
     */
    public function getHotelBookings(): Collection
    {
        return $this->hotelBookings;
    }

    public function addHotelBooking(HotelBooking $hotelBooking): static
    {
        if (!$this->hotelBookings->contains($hotelBooking)) {
            $this->hotelBookings->add($hotelBooking);
            $hotelBooking->setHotel($this);
        }

        return $this;
    }

    public function removeHotelBooking(HotelBooking $hotelBooking): static
    {
        if ($this->hotelBookings->removeElement($hotelBooking)) {
            // set the owning side to null (unless already changed)
            if ($hotelBooking->getHotel() === $this) {
                $hotelBooking->setHotel(null);
            }
        }

        return $this;
    }
}
