<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\VoucherRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: VoucherRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "DESC"],
        "normalization_context" => ["groups" => ["voucherReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "voucher"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
#[ORM\HasLifecycleCallbacks]
class Voucher
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['voucherReduced', 'voucher'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'vouchers')]
    #[Groups(['voucherReduced', 'voucher'])]
    private ?Hotel $hotel = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['voucherReduced', 'voucher'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['voucherReduced', 'voucher'])]
    private ?string $toBePaidBy = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Groups(['voucherReduced', 'voucher'])]
    #[ApiSubresource]
    private ?Booking $booking = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['voucherReduced', 'voucher'])]
    private ?string $observations = null;

    #[ORM\PrePersist]
    public function onPrePersist()
    {
        $this->createdAt = new \DateTimeImmutable('now');
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getToBePaidBy(): ?string
    {
        return $this->toBePaidBy;
    }

    public function setToBePaidBy(?string $toBePaidBy): static
    {
        $this->toBePaidBy = $toBePaidBy;

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

    public function getObservations(): ?string
    {
        return $this->observations;
    }

    public function setObservations(?string $observations): static
    {
        $this->observations = $observations;

        return $this;
    }
}
