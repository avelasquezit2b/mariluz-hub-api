<?php

namespace App\Entity;

use App\Repository\BillRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BillRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["billReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "bill"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class Bill
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['billReduced', 'bill'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['billReduced', 'bill'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['billReduced', 'bill'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['billReduced', 'bill'])]
    private ?string $totalPrice = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['billReduced', 'bill'])]
    private ?string $totalAmount = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['billReduced', 'bill'])]
    private ?string $totalPriceWithoutTaxes = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getTotalPrice(): ?string
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(?string $totalPrice): static
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getTotalAmount(): ?string
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(?string $totalAmount): static
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function getTotalPriceWithoutTaxes(): ?string
    {
        return $this->totalPriceWithoutTaxes;
    }

    public function setTotalPriceWithoutTaxes(?string $totalPriceWithoutTaxes): static
    {
        $this->totalPriceWithoutTaxes = $totalPriceWithoutTaxes;

        return $this;
    }
}
