<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use App\Repository\HotelAvailabilityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: HotelAvailabilityRepository::class)]
#[ApiResource(
    paginationEnabled: false,
    attributes: [
        // "order" => ["activityPrices.activitySchedule.startTime" => "ASC"],
        "normalization_context" => ["groups" => ["hotelAvailabilityReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "hotelAvailability"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
#[ApiFilter(DateFilter::class, properties: ['date'])]
#[ApiFilter(SearchFilter::class, properties: ['roomCondition.hotelSeason.hotelFee.hotel' => 'exact', 'roomCondition.hotelSeason.hotelFee.hotel.zones.name' => 'exact'])]
#[ApiFilter(RangeFilter::class, properties: ['quota'])]
#[ApiFilter(BooleanFilter::class, properties: ['isAvailable', 'roomCondition.hotelSeason.hotelFee.hotel.isActive'])]
#[ORM\HasLifecycleCallbacks]
class HotelAvailability
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['hotelAvailabilityReduced', 'hotelAvailability'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['hotelAvailabilityReduced', 'hotelAvailability'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    #[Groups(['hotelAvailabilityReduced', 'hotelAvailability'])]
    public ?int $quota = null;

    #[ORM\Column]
    #[Groups(['hotelAvailabilityReduced', 'hotelAvailability'])]
    private ?bool $isAvailable = true;

    #[ORM\ManyToOne(inversedBy: 'hotelAvailabilities')]
    #[Groups(['hotelAvailabilityReduced', 'hotelAvailability'])]
    private ?RoomCondition $roomCondition = null;

    #[ORM\Column]
    #[Groups(['hotelAvailabilityReduced', 'hotelAvailability'])]
    private ?int $maxQuota = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelAvailabilityReduced', 'hotelAvailability'])]
    private ?int $totalBookings = 0;

    public function __construct()
    {
    }

    #[ORM\PreUpdate]
    public function onPreUpdate($request)
    {
        if ($this->getQuota() > $this->getMaxQuota()) {
            $this->setQuota($this->getMaxQuota());
        } else if (($this->getMaxQuota() - $this->getTotalBookings()) != $this->getQuota()) {
            $this->setQuota(($this->getMaxQuota() - $this->getTotalBookings()));
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getQuota(): ?int
    {
        return $this->quota;
    }

    public function setQuota(int $quota): static
    {
        $this->quota = $quota;

        return $this;
    }

    public function isIsAvailable(): ?bool
    {
        return $this->isAvailable;
    }

    public function setIsAvailable(bool $isAvailable): static
    {
        $this->isAvailable = $isAvailable;

        return $this;
    }

    public function getRoomCondition(): ?RoomCondition
    {
        return $this->roomCondition;
    }

    public function setRoomCondition(?RoomCondition $roomCondition): static
    {
        $this->roomCondition = $roomCondition;

        return $this;
    }

    public function getMaxQuota(): ?int
    {
        return $this->maxQuota;
    }

    public function setMaxQuota(int $maxQuota): static
    {
        $this->maxQuota = $maxQuota;

        return $this;
    }

    public function getTotalBookings(): ?int
    {
        return $this->totalBookings;
    }

    public function setTotalBookings(?int $totalBookings): static
    {
        $this->totalBookings = $totalBookings;

        return $this;
    }
}
