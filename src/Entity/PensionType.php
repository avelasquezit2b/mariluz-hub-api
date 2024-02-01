<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PensionTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PensionTypeRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["pensionTypeReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "pensionType"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class PensionType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['pensionTypeReduced', 'pensionType', 'hotel'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['pensionTypeReduced', 'pensionType', 'hotel'])]
    private ?string $title = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['pensionTypeReduced', 'pensionType', 'hotel'])]
    private ?string $code = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['pensionTypeReduced', 'pensionType', 'hotel'])]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: HotelServices::class, mappedBy: 'pensionTypes')]
    #[Groups(['pensionTypeReduced', 'pensionType'])]
    private Collection $hotelServices;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['pensionTypeReduced', 'pensionType', 'hotel'])]
    private ?string $customTitle = null;

    #[ORM\OneToMany(mappedBy: 'pensionType', targetEntity: PensionTypePrice::class)]
    #[Groups(['pensionTypeReduced', 'pensionType'])]
    private Collection $pensionTypePrices;

    #[ORM\ManyToMany(targetEntity: HotelFee::class, mappedBy: 'pensionTypes')]
    #[Groups(['pensionTypeReduced', 'pensionType'])]
    private Collection $hotelFees;

    public function __construct()
    {
        $this->hotelServices = new ArrayCollection();
        $this->pensionTypePrices = new ArrayCollection();
        $this->hotelFees = new ArrayCollection();
    }

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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, HotelServices>
     */
    public function getHotelServices(): Collection
    {
        return $this->hotelServices;
    }

    public function addHotelService(HotelServices $hotelService): static
    {
        if (!$this->hotelServices->contains($hotelService)) {
            $this->hotelServices->add($hotelService);
            $hotelService->addPensionType($this);
        }

        return $this;
    }

    public function removeHotelService(HotelServices $hotelService): static
    {
        if ($this->hotelServices->removeElement($hotelService)) {
            $hotelService->removePensionType($this);
        }

        return $this;
    }

    public function getCustomTitle(): ?string
    {
        return $this->customTitle;
    }

    public function setCustomTitle(?string $customTitle): static
    {
        $this->customTitle = $customTitle;

        return $this;
    }

    /**
     * @return Collection<int, PensionTypePrice>
     */
    public function getPensionTypePrices(): Collection
    {
        return $this->pensionTypePrices;
    }

    public function addPensionTypePrice(PensionTypePrice $pensionTypePrice): static
    {
        if (!$this->pensionTypePrices->contains($pensionTypePrice)) {
            $this->pensionTypePrices->add($pensionTypePrice);
            $pensionTypePrice->setPensionType($this);
        }

        return $this;
    }

    public function removePensionTypePrice(PensionTypePrice $pensionTypePrice): static
    {
        if ($this->pensionTypePrices->removeElement($pensionTypePrice)) {
            // set the owning side to null (unless already changed)
            if ($pensionTypePrice->getPensionType() === $this) {
                $pensionTypePrice->setPensionType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, HotelFee>
     */
    public function getHotelFees(): Collection
    {
        return $this->hotelFees;
    }

    public function addHotelFee(HotelFee $hotelFee): static
    {
        if (!$this->hotelFees->contains($hotelFee)) {
            $this->hotelFees->add($hotelFee);
            $hotelFee->addPensionType($this);
        }

        return $this;
    }

    public function removeHotelFee(HotelFee $hotelFee): static
    {
        if ($this->hotelFees->removeElement($hotelFee)) {
            $hotelFee->removePensionType($this);
        }

        return $this;
    }
}
