<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ActivityBookingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ActivityBookingRepository::class)]
#[ApiResource(
    paginationEnabled: false,
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["activityBookingReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "activityBooking"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class ActivityBooking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['activityBookingReduced', 'activityBooking'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['activityBookingReduced', 'activityBooking'])]
    private ?\DateTimeInterface $checkIn = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['activityBookingReduced', 'activityBooking'])]
    private ?\DateTimeInterface $checkOut = null;

    #[ORM\Column(length: 255)]
    #[Groups(['activityBookingReduced', 'activityBooking'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['activityBookingReduced', 'activityBooking'])]
    private ?string $email = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['activityBookingReduced', 'activityBooking'])]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['activityBookingReduced'])]
    private ?string $observations = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['activityBookingReduced'])]
    private ?string $promoCode = null;

    #[ORM\Column(length: 25)]
    #[Groups(['activityBookingReduced', 'activityBooking'])]
    private ?string $totalPrice = null;

    #[ORM\Column(length: 25)]
    #[Groups(['activityBookingReduced', 'activityBooking'])]
    private ?string $paymentMethod = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    #[Groups(['activityBookingReduced'])]
    private ?array $data = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['activityBookingReduced'])]
    private ?bool $hasAcceptance = null;

    #[ORM\Column(length: 25)]
    #[Groups(['activityBookingReduced', 'activityBooking'])]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'activityBookings')]
    #[Groups(['activityBookingReduced', 'activityBooking'])]
    private ?Activity $activity = null;

    #[ORM\ManyToMany(targetEntity: ActivityAvailability::class, inversedBy: 'activityBookings')]
    #[Groups(['activityBookingReduced', 'activityBooking'])]
    private Collection $activityAvailabilities;

    #[ORM\ManyToOne(inversedBy: 'activityBookings')]
    private ?Client $client = null;

    public function __construct()
    {
        $this->activityAvailabilities = new ArrayCollection();
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

    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    public function setActivity(?Activity $activity): static
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * @return Collection<int, ActivityAvailability>
     */
    public function getActivityAvailabilities(): Collection
    {
        return $this->activityAvailabilities;
    }

    public function addActivityAvailability(ActivityAvailability $activityAvailability): static
    {
        if (!$this->activityAvailabilities->contains($activityAvailability)) {
            $this->activityAvailabilities->add($activityAvailability);
        }

        return $this;
    }

    public function removeActivityAvailability(ActivityAvailability $activityAvailability): static
    {
        $this->activityAvailabilities->removeElement($activityAvailability);

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
}
