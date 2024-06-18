<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BookingLineRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BookingLineRepository::class)]
#[ApiResource(
    paginationEnabled: false,
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["bookingLineReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "bookingLine"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class BookingLine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['booking', 'bookingReduced', 'bookingLineReduced', 'bookingLine', 'tickets'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['bookingLineReduced', 'bookingLine', 'booking', 'bookingReduced', 'voucherReduced', 'voucher', 'tickets'])]
    private ?\DateTimeInterface $checkIn = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['bookingLineReduced', 'bookingLine', 'booking', 'bookingReduced', 'voucherReduced', 'voucher', 'tickets'])]
    private ?\DateTimeInterface $checkOut = null;

    #[ORM\Column(length: 25)]
    #[Groups(['bookingLineReduced', 'bookingLine', 'booking', 'voucherReduced', 'voucher', 'tickets'])]
    private ?string $totalPrice = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    #[Groups(['bookingLineReduced', 'bookingLine', 'booking', 'voucherReduced', 'voucher', 'tickets'])]
    private ?array $data = null;

    #[ORM\ManyToOne(inversedBy: 'bookingLines')]
    #[Groups(['bookingLineReduced', 'bookingLine', 'booking', 'bookingReduced', 'voucherReduced', 'voucher', 'tickets'])]
    private ?Hotel $hotel = null;

    #[ORM\ManyToOne(inversedBy: 'bookingLines')]
    #[Groups(['bookingLineReduced', 'bookingLine', 'booking', 'bookingReduced', 'voucherReduced', 'voucher', 'tickets'])]
    private ?Activity $activity = null;

    #[ORM\ManyToOne(inversedBy: 'bookingLines')]
    #[Groups(['bookingLineReduced', 'bookingLine', 'booking', 'voucherReduced', 'voucher', 'tickets'])]
    private ?Booking $booking = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['bookingLineReduced', 'bookingLine', 'booking', 'voucherReduced', 'voucher', 'tickets'])]
    private ?string $totalPriceCost = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCheckIn()
    {
        if ($this->checkIn) {
            return $this->checkIn->format('d-m-Y');
        } else {
            return $this->checkIn;
        }
    }

    public function setCheckIn(?\DateTimeInterface $checkIn): static
    {
        $this->checkIn = $checkIn;

        return $this;
    }

    public function getCheckOut()
    {
        if($this->checkOut){
            return $this->checkOut->format('d-m-Y');
        } else {
            return $this->checkOut;
        }
    }

    public function setCheckOut(?\DateTimeInterface $checkOut): static
    {
        $this->checkOut = $checkOut;

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

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(?array $data): static
    {
        $this->data = $data;

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

    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    public function setActivity(?Activity $activity): static
    {
        $this->activity = $activity;

        return $this;
    }

    public function getBooking(): ?Booking
    {
        return $this->booking;
    }

    public function setBooking(?Booking $booking): static
    {
        $this->booking = $booking;

        return $this;
    }

    public function getTotalPriceCost(): ?string
    {
        return $this->totalPriceCost;
    }

    public function setTotalPriceCost(?string $totalPriceCost): static
    {
        $this->totalPriceCost = $totalPriceCost;

        return $this;
    }
}
