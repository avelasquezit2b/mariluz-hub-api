<?php

namespace App\Entity;

use App\Repository\ConfigurationRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ConfigurationRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["configReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "config"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class Configuration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['configReduced', 'config'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['configReduced', 'config'])]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['configReduced', 'config'])]
    private ?string $cif = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['configReduced', 'config'])]
    private ?string $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['configReduced', 'config'])]
    private ?string $postalCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['configReduced', 'config'])]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['configReduced', 'config'])]
    private ?string $fax = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['configReduced', 'config'])]
    private ?string $city = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['configReduced', 'config'])]
    private ?string $province = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['configReduced', 'config'])]
    private ?string $country = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['configReduced', 'config'])]
    private ?string $account = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['configReduced', 'config'])]
    private ?string $billFooter = null;

    #[ORM\OneToOne(inversedBy: 'configuration', cascade: ['persist', 'remove'])]
    #[Groups(['configReduced', 'config'])]
    private ?MediaObject $logo = null;

    #[ORM\Column]
    #[Groups(['configReduced', 'config'])]
    private ?bool $hasHotels = null;

    #[ORM\Column]
    #[Groups(['configReduced', 'config'])]
    private ?bool $hasActivities = null;

    #[ORM\Column]
    #[Groups(['configReduced', 'config'])]
    private ?bool $hasExtras = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['configReduced', 'config'])]
    private ?string $bookingEmail = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getCif(): ?string
    {
        return $this->cif;
    }

    public function setCif(?string $cif): static
    {
        $this->cif = $cif;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): static
    {
        $this->postalCode = $postalCode;

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

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function setFax(?string $fax): static
    {
        $this->fax = $fax;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getProvince(): ?string
    {
        return $this->province;
    }

    public function setProvince(?string $province): static
    {
        $this->province = $province;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getAccount(): ?string
    {
        return $this->account;
    }

    public function setAccount(?string $account): static
    {
        $this->account = $account;

        return $this;
    }

    public function getBillFooter(): ?string
    {
        return $this->billFooter;
    }

    public function setBillFooter(?string $billFooter): static
    {
        $this->billFooter = $billFooter;

        return $this;
    }

    public function getLogo(): ?MediaObject
    {
        return $this->logo;
    }

    public function setLogo(?MediaObject $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    public function isHasHotels(): ?bool
    {
        return $this->hasHotels;
    }

    public function setHasHotels(bool $hasHotels): static
    {
        $this->hasHotels = $hasHotels;

        return $this;
    }

    public function isHasActivities(): ?bool
    {
        return $this->hasActivities;
    }

    public function setHasActivities(bool $hasActivities): static
    {
        $this->hasActivities = $hasActivities;

        return $this;
    }

    public function isHasExtras(): ?bool
    {
        return $this->hasExtras;
    }

    public function setHasExtras(bool $hasExtras): static
    {
        $this->hasExtras = $hasExtras;

        return $this;
    }

    public function getBookingEmail(): ?string
    {
        return $this->bookingEmail;
    }

    public function setBookingEmail(?string $bookingEmail): static
    {
        $this->bookingEmail = $bookingEmail;

        return $this;
    }
}
