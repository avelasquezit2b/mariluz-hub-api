<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BookingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BookingRepository::class)]
#[ApiResource(
    paginationEnabled: false,
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["bookingReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "booking"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class Booking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['bookingReduced', 'booking'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['bookingReduced', 'booking'])]
    private ?\DateTimeInterface $checkIn = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['bookingReduced', 'booking'])]
    private ?\DateTimeInterface $checkOut = null;

    #[ORM\Column(length: 255)]
    #[Groups(['bookingReduced', 'booking'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['bookingReduced', 'booking'])]
    private ?string $email = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['bookingReduced', 'booking'])]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['bookingReduced', 'booking'])]
    private ?string $observations = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['bookingReduced', 'booking'])]
    private ?string $promoCode = null;

    #[ORM\Column(length: 25)]
    #[Groups(['bookingReduced', 'booking'])]
    private ?string $totalPrice = null;

    #[ORM\Column(length: 25)]
    #[Groups(['bookingReduced', 'booking'])]
    private ?string $paymentMethod = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    #[Groups(['bookingReduced', 'booking'])]
    private ?array $data = null;

    #[ORM\Column]
    #[Groups(['bookingReduced', 'booking'])]
    private ?bool $hasAcceptance = null;

    #[ORM\Column(length: 25)]
    #[Groups(['bookingReduced', 'booking'])]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[Groups(['bookingReduced', 'booking', 'voucherReduced', 'voucher'])]
    private ?Client $client = null;

    #[ORM\OneToMany(mappedBy: 'booking', targetEntity: BookingLine::class)]
    #[Groups(['bookingReduced', 'booking'])]
    private Collection $bookingLines;

    public function __construct()
    {
        $this->bookingLines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCheckIn(): ?\DateTimeInterface
    {
        return $this->checkIn;
    }

    public function setCheckIn(?\DateTimeInterface $checkIn): static
    {
        $this->checkIn = $checkIn;

        return $this;
    }

    public function getCheckOut(): ?\DateTimeInterface
    {
        return $this->checkOut;
    }

    public function setCheckOut(?\DateTimeInterface $checkOut): static
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

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(?array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function isHasAcceptance(): ?bool
    {
        return $this->hasAcceptance;
    }

    public function setHasAcceptance(bool $hasAcceptance): static
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

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection<int, BookingLine>
     */
    public function getBookingLines(): Collection
    {
        return $this->bookingLines;
    }

    public function addBookingLine(BookingLine $bookingLine): static
    {
        if (!$this->bookingLines->contains($bookingLine)) {
            $this->bookingLines->add($bookingLine);
            $bookingLine->setBooking($this);
        }

        return $this;
    }

    public function removeBookingLine(BookingLine $bookingLine): static
    {
        if ($this->bookingLines->removeElement($bookingLine)) {
            // set the owning side to null (unless already changed)
            if ($bookingLine->getBooking() === $this) {
                $bookingLine->setBooking(null);
            }
        }

        return $this;
    }
}
