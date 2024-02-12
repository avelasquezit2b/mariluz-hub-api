<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\HotelFeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: HotelFeeRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["hotelFeeReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "hotelFee"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class HotelFee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['hotelFeeReduced', 'hotelFee'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['hotelFeeReduced', 'hotelFee'])]
    private ?string $title = null;

    #[ORM\ManyToMany(targetEntity: OccupancyType::class, inversedBy: 'hotelFees')]
    private Collection $OccupancyType;

    #[ORM\ManyToMany(targetEntity: CancellationType::class, inversedBy: 'hotelFees')]
    #[Groups(['hotelFeeReduced', 'hotelFee'])]
    private Collection $cancellationTypes;

    #[ORM\OneToMany(mappedBy: 'hotelFee', targetEntity: HotelSeason::class, cascade: ['remove'])]
    #[Groups(['hotelFeeReduced', 'hotelFee'])]
    private Collection $hotelSeasons;

    #[ORM\ManyToMany(targetEntity: PensionType::class, inversedBy: 'hotelFees')]
    #[Groups(['hotelFeeReduced', 'hotelFee'])]
    private Collection $pensionTypes;

    #[ORM\ManyToOne(inversedBy: 'hotelFees')]
    // #[Groups(['hotelFeeReduced', 'hotelFee'])]
    private ?Hotel $hotel = null;

    public function __construct()
    {
        $this->OccupancyType = new ArrayCollection();
        $this->cancellationTypes = new ArrayCollection();
        $this->hotelSeasons = new ArrayCollection();
        $this->pensionTypes = new ArrayCollection();
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

    /**
     * @return Collection<int, OccupancyType>
     */
    public function getOccupancyType(): Collection
    {
        return $this->OccupancyType;
    }

    public function addOccupancyType(OccupancyType $occupancyType): static
    {
        if (!$this->OccupancyType->contains($occupancyType)) {
            $this->OccupancyType->add($occupancyType);
        }

        return $this;
    }

    public function removeOccupancyType(OccupancyType $occupancyType): static
    {
        $this->OccupancyType->removeElement($occupancyType);

        return $this;
    }

    /**
     * @return Collection<int, CancellationType>
     */
    public function getCancellationTypes(): Collection
    {
        return $this->cancellationTypes;
    }

    public function addCancellationType(CancellationType $cancellationType): static
    {
        if (!$this->cancellationTypes->contains($cancellationType)) {
            $this->cancellationTypes->add($cancellationType);
        }

        return $this;
    }

    public function removeCancellationType(CancellationType $cancellationType): static
    {
        $this->cancellationTypes->removeElement($cancellationType);

        return $this;
    }

    /**
     * @return Collection<int, HotelSeason>
     */
    public function getHotelSeasons(): Collection
    {
        return $this->hotelSeasons;
    }

    public function addHotelSeason(HotelSeason $hotelSeason): static
    {
        if (!$this->hotelSeasons->contains($hotelSeason)) {
            $this->hotelSeasons->add($hotelSeason);
            $hotelSeason->setHotelFee($this);
        }

        return $this;
    }

    public function removeHotelSeason(HotelSeason $hotelSeason): static
    {
        if ($this->hotelSeasons->removeElement($hotelSeason)) {
            // set the owning side to null (unless already changed)
            if ($hotelSeason->getHotelFee() === $this) {
                $hotelSeason->setHotelFee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PensionType>
     */
    public function getPensionTypes(): Collection
    {
        return $this->pensionTypes;
    }

    public function addPensionType(PensionType $pensionType): static
    {
        if (!$this->pensionTypes->contains($pensionType)) {
            $this->pensionTypes->add($pensionType);
        }

        return $this;
    }

    public function removePensionType(PensionType $pensionType): static
    {
        $this->pensionTypes->removeElement($pensionType);

        return $this;
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
}
