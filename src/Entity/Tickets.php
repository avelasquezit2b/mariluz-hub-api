<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TicketsRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketsRepository::class)]
#[ApiResource(
    itemOperations: [
        'get' => [
            'normalization_context' => ['groups' => 'tickets']
        ],
        'put',
        'delete'
    ]
)]
class Tickets
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['tickets', 'booking'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['tickets', 'booking'])]
    private ?string $passType = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['tickets', 'booking'])]
    private ?int $price = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['tickets', 'booking'])]
    private ?string $productName = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[Groups(['tickets', 'booking'])]
    private ?Booking $booking = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPassType(): ?string
    {
        return $this->passType;
    }

    public function setPassType(?string $passType): static
    {
        $this->passType = $passType;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(?string $productName): static
    {
        $this->productName = $productName;

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
