<?php

namespace App\Entity;

use App\Repository\BillRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
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
    private ?string $pricePayed = null;

    #[ORM\ManyToOne(inversedBy: 'bills')]
    #[Groups(['billReduced', 'bill'])]
    private ?Client $client = null;

    #[ORM\OneToMany(mappedBy: 'bill', targetEntity: HotelBooking::class)]
    #[Groups(['billReduced', 'bill'])]
    private Collection $booking;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['billReduced', 'bill'])]
    private ?string $aditionalDescription = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['billReduced', 'bill'])]
    private ?string $accountingCode = null;

    public function __construct()
    {
        $this->booking = new ArrayCollection();
    }

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
        //set automatically the created_at date
        if ($createdAt == null) {
            $this->createdAt = new \DateTime();
        } else {
            $this->createdAt = $createdAt;
        }

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
     * @return Collection<int, HotelBooking>
     */
    public function getBooking(): Collection
    {
        return $this->booking;
    }

    public function addBooking(HotelBooking $booking): static
    {
        if (!$this->booking->contains($booking)) {
            $this->booking->add($booking);
            $booking->setBill($this);
        }

        return $this;
    }

    public function removeBooking(HotelBooking $booking): static
    {
        if ($this->booking->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getBill() === $this) {
                $booking->setBill(null);
            }
        }

        return $this;
    }

    public function getAditionalDescription(): ?string
    {
        return $this->aditionalDescription;
    }

    public function setAditionalDescription(?string $aditionalDescription): static
    {
        $this->aditionalDescription = $aditionalDescription;

        return $this;
    }

    public function getPricePayed(): ?string
    {
        return $this->pricePayed;
    }

    public function setPricePayed(?string $pricePayed): static
    {
        $this->pricePayed = $pricePayed;

        return $this;
    }

    public function getAccountingCode(): ?string
    {
        return $this->accountingCode;
    }

    public function setAccountingCode(?string $accountingCode): static
    {
        $this->accountingCode = $accountingCode;

        return $this;
    }
}
