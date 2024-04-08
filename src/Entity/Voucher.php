<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\VoucherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VoucherRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
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
    private ?Booking $booking = null;
    
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
        //set automatically the created_at date
        if ($createdAt == null) {
            $this->createdAt = new \DateTime();
        } else {
            $this->createdAt = $createdAt;
        }

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
}
