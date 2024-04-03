<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\HotelBookingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: HotelBookingRepository::class)]
#[ApiResource(
    paginationEnabled: false,
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["hotelBookingReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "hotelBooking"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class HotelBooking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['hotelBookingReduced', 'hotelBooking'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['hotelBookingReduced', 'hotelBooking'])]
    private ?\DateTimeInterface $checkIn = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['hotelBookingReduced', 'hotelBooking'])]
    private ?\DateTimeInterface $checkOut = null;

    #[ORM\Column(length: 255)]
    #[Groups(['hotelBookingReduced', 'hotelBooking'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['hotelBookingReduced', 'hotelBooking'])]
    private ?string $email = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['hotelBookingReduced', 'hotelBooking'])]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['hotelBooking'])]
    private ?string $observations = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['hotelBooking'])]
    private ?string $promoCode = null;

    #[ORM\Column(length: 25)]
    #[Groups(['hotelBookingReduced', 'hotelBooking'])]
    private ?string $totalPrice = null;

    #[ORM\Column(length: 25)]
    #[Groups(['hotelBookingReduced', 'hotelBooking'])]
    private ?string $paymentMethod = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    #[Groups(['hotelBooking'])]
    public ?array $rooms = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelBooking'])]
    private ?bool $hasAcceptance = null;

    #[ORM\Column(length: 25)]
    #[Groups(['hotelBookingReduced', 'hotelBooking'])]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'hotelBookings')]
    #[Groups(['hotelBookingReduced', 'hotelBooking'])]
    private ?Hotel $hotel = null;

    #[ORM\ManyToMany(targetEntity: HotelAvailability::class, mappedBy: 'hotelBookings')]
    #[Groups(['hotelBookingReduced', 'hotelBooking'])]
    private Collection $hotelAvailabilities;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    private ?Client $client = null;

    #[ORM\ManyToOne(inversedBy: 'booking')]
    private ?Bill $bill = null;

    public function __construct()
    {
        $this->hotelAvailabilities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCheckIn(): ?\DateTimeInterface
    {
        return $this->checkIn;
    }

    public function setCheckIn(\DateTimeInterface $checkIn): static
    {
        $this->checkIn = $checkIn;

        return $this;
    }

    public function getCheckOut(): ?\DateTimeInterface
    {
        return $this->checkOut;
    }

    public function setCheckOut(\DateTimeInterface $checkOut): static
    {
        $this->checkOut = $checkOut;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getObservations(): ?string
    {
        return $this->observations;
    }

    public function setObservations(?string $observations): static
    {
        $this->observations = $observations;

        return $this;
    }

    public function getPromoCode(): ?string
    {
        return $this->promoCode;
    }

    public function setPromoCode(?string $promoCode): static
    {
        $this->promoCode = $promoCode;

        return $this;
    }

    public function getTotalPrice(): ?string
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(string $totalPrice): static
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(string $paymentMethod): static
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    public function getRooms(): ?array
    {
        return $this->rooms;
    }

    public function setRooms(?array $rooms): static
    {
        $this->rooms = $rooms;

        return $this;
    }

    public function isHasAcceptance(): ?bool
    {
        return $this->hasAcceptance;
    }

    public function setHasAcceptance(?bool $hasAcceptance): static
    {
        $this->hasAcceptance = $hasAcceptance;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

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
            $hotelAvailability->addHotelBooking($this);
        }

        return $this;
    }

    public function removeHotelAvailability(HotelAvailability $hotelAvailability): static
    {
        if ($this->hotelAvailabilities->removeElement($hotelAvailability)) {
            $hotelAvailability->removeHotelBooking($this);
        }

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getBill(): ?Bill
    {
        return $this->bill;
    }

    public function setBill(?Bill $bill): static
    {
        $this->bill = $bill;

        return $this;
    }
}
