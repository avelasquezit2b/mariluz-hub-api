<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\HotelSeasonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: HotelSeasonRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["hotelSeasonReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "hotelSeason"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class HotelSeason
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['hotelSeasonReduced', 'hotelSeason', 'hotelFeeReduced'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['hotelSeasonReduced', 'hotelSeason', 'hotelFeeReduced'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    #[Groups(['hotelSeasonReduced', 'hotelSeason', 'hotelFeeReduced'])]
    private ?array $ranges = null;

    #[ORM\ManyToOne(inversedBy: 'hotelSeasons')]
    #[Groups(['hotelSeasonReduced', 'hotelSeason', 'hotelAvailabilityReduced'])]
    private ?HotelFee $hotelFee = null;

    #[ORM\OneToMany(mappedBy: 'hotelSeason', targetEntity: RoomCondition::class, cascade: ['remove'])]
    #[Groups(['hotelSeasonReduced', 'hotelSeason', 'hotelFeeReduced'])]
    private Collection $roomConditions;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelSeasonReduced', 'hotelSeason', 'hotelFeeReduced', 'hotelAvailability'])]
    private ?bool $isOnRequest = null;

    public function __construct()
    {
        $this->roomConditions = new ArrayCollection();
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

    public function getRanges(): ?array
    {
        return $this->ranges;
    }

    public function setRanges(?array $ranges): static
    {
        $this->ranges = $ranges;

        return $this;
    }

    public function getHotelFee(): ?HotelFee
    {
        return $this->hotelFee;
    }

    public function setHotelFee(?HotelFee $hotelFee): static
    {
        $this->hotelFee = $hotelFee;

        return $this;
    }

    /**
     * @return Collection<int, RoomCondition>
     */
    public function getRoomConditions(): Collection
    {
        return $this->roomConditions;
    }

    public function addRoomCondition(RoomCondition $roomCondition): static
    {
        if (!$this->roomConditions->contains($roomCondition)) {
            $this->roomConditions->add($roomCondition);
            $roomCondition->setHotelSeason($this);
        }

        return $this;
    }

    public function removeRoomCondition(RoomCondition $roomCondition): static
    {
        if ($this->roomConditions->removeElement($roomCondition)) {
            // set the owning side to null (unless already changed)
            if ($roomCondition->getHotelSeason() === $this) {
                $roomCondition->setHotelSeason(null);
            }
        }

        return $this;
    }

    public function isIsOnRequest(): ?bool
    {
        return $this->isOnRequest;
    }

    public function setIsOnRequest(?bool $isOnRequest): static
    {
        $this->isOnRequest = $isOnRequest;

        return $this;
    }
}
