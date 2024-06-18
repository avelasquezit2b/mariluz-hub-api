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
        "order" => ["id" => "DESC"],
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
#[ORM\HasLifecycleCallbacks]
class Booking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['bookingReduced', 'booking', 'voucher', 'voucherReduced', 'tickets'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    // #[Groups(['bookingReduced', 'booking'])]
    private ?\DateTimeInterface $checkIn = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    // #[Groups(['bookingReduced', 'booking'])]
    private ?\DateTimeInterface $checkOut = null;

    #[ORM\Column(length: 255)]
    #[Groups(['bookingReduced', 'booking', 'voucherReduced', 'tickets'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['bookingReduced', 'booking', 'tickets'])]
    private ?string $email = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['bookingReduced', 'booking', 'tickets'])]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['bookingReduced', 'booking', 'tickets'])]
    private ?string $observations = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['bookingReduced', 'booking', 'tickets'])]
    private ?string $promoCode = null;

    #[ORM\Column(length: 25)]
    #[Groups(['bookingReduced', 'booking', 'tickets'])]
    private ?string $totalPrice = null;

    #[ORM\Column(length: 25)]
    #[Groups(['bookingReduced', 'booking', 'tickets'])]
    private ?string $paymentMethod = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    // #[Groups(['bookingReduced', 'booking'])]
    private ?array $data = null;

    #[ORM\Column]
    #[Groups(['bookingReduced', 'booking', 'tickets'])]
    private ?bool $hasAcceptance = null;

    #[ORM\Column(length: 25)]
    #[Groups(['bookingReduced', 'booking', 'tickets'])]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[Groups(['bookingReduced', 'booking', 'voucher', 'tickets'])]
    private ?Client $client = null;

    #[ORM\OneToMany(mappedBy: 'booking', targetEntity: BookingLine::class, cascade: ['remove'])]
    #[Groups(['bookingReduced', 'booking', 'voucher', 'tickets'])]
    private Collection $bookingLines;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['bookingReduced', 'booking', 'tickets'])]
    private ?string $paymentStatus = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['bookingReduced', 'booking', 'tickets'])]
    private ?string $onRequestPayment = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['bookingReduced', 'booking', 'tickets'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['bookingReduced', 'booking', 'tickets'])]
    private ?string $internalNotes = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['bookingReduced', 'booking', 'tickets'])]
    private ?string $totalPriceCost = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['bookingReduced', 'booking', 'tickets'])]
    private ?bool $clientConfirmationSent = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['bookingReduced', 'booking', 'tickets'])]
    private ?bool $supplierConfirmationSent = null;

    #[ORM\OneToMany(mappedBy: 'booking', targetEntity: Tickets::class)]
    private Collection $tickets;

    public function __construct()
    {
        $this->bookingLines = new ArrayCollection();
        $this->tickets = new ArrayCollection();
    }

    #[ORM\PrePersist]
    public function onPrePersist()
    {
        $this->createdAt = new \DateTimeImmutable('now');
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

    public function getPaymentStatus(): ?string
    {
        return $this->paymentStatus;
    }

    public function setPaymentStatus(?string $paymentStatus): static
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }

    public function getOnRequestPayment(): ?string
    {
        return $this->onRequestPayment;
    }

    public function setOnRequestPayment(?string $onRequestPayment): static
    {
        $this->onRequestPayment = $onRequestPayment;

        return $this;
    }

    public function getCreatedAt()
    {
        if($this->createdAt){
            return $this->createdAt->format('d-m-Y');
        } else {
            return $this->createdAt;
        }
        
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        
        return $this;
    }

    public function getInternalNotes(): ?string
    {
        return $this->internalNotes;
    }

    public function setInternalNotes(?string $internalNotes): static
    {
        $this->internalNotes = $internalNotes;

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

    public function isClientConfirmationSent(): ?bool
    {
        return $this->clientConfirmationSent;
    }

    public function setClientConfirmationSent(?bool $clientConfirmationSent): static
    {
        $this->clientConfirmationSent = $clientConfirmationSent;

        return $this;
    }

    public function isSupplierConfirmationSent(): ?bool
    {
        return $this->supplierConfirmationSent;
    }

    public function setSupplierConfirmationSent(?bool $supplierConfirmationSent): static
    {
        $this->supplierConfirmationSent = $supplierConfirmationSent;

        return $this;
    }

    /**
     * @return Collection<int, Tickets>
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Tickets $ticket): static
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets->add($ticket);
            $ticket->setBooking($this);
        }

        return $this;
    }

    public function removeTicket(Tickets $ticket): static
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getBooking() === $this) {
                $ticket->setBooking(null);
            }
        }

        return $this;
    }
}
