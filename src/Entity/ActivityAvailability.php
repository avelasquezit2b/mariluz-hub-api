<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use App\Repository\ActivityAvailabilityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ActivityAvailabilityRepository::class)]
#[ApiResource(
    paginationEnabled: false,
    attributes: [
        // "order" => ["activityPrices.activitySchedule.startTime" => "ASC"],
        "normalization_context" => ["groups" => ["activityAvailabilityReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "activityAvailability"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
#[ApiFilter(DateFilter::class, properties: ['date'])]
#[ApiFilter(SearchFilter::class, properties: ['activitySchedule.activitySeason.activityFee.activity' => 'exact', 'activitySchedule.activitySeason.activityFee.activity.zones.name' => 'exact', 'activitySchedule.activitySeason.activityFee.activity.location.name' => 'exact'])]
#[ApiFilter(RangeFilter::class, properties: ['quota'])]
#[ApiFilter(OrderFilter::class, properties: ['activitySchedule.startTime' => 'ASC'])]
#[ORM\HasLifecycleCallbacks]
class ActivityAvailability
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['activityAvailabilityReduced', 'activityAvailability'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['activityAvailabilityReduced', 'activityAvailability'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    #[Groups(['activityAvailabilityReduced', 'activityAvailability'])]
    public ?int $quota = 0;

    #[ORM\Column]
    #[Groups(['activityAvailabilityReduced', 'activityAvailability'])]
    private ?bool $isAvailable = true;

    #[ORM\ManyToOne(inversedBy: 'activityAvailabilities')]
    #[Groups(['activityAvailabilityReduced', 'activityAvailability'])]
    private ?ActivitySchedule $activitySchedule = null;

    #[ORM\Column]
    #[Groups(['activityAvailabilityReduced', 'activityAvailability'])]
    private ?int $maxQuota = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['activityAvailabilityReduced', 'activityAvailability'])]
    private ?int $totalBookings = 0;

    public function __construct()
    {
        $this->activityPrices = new ArrayCollection();
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

    public function getActivitySchedule(): ?ActivitySchedule
    {
        return $this->activitySchedule;
    }

    public function setActivitySchedule(?ActivitySchedule $activitySchedule): static
    {
        $this->activitySchedule = $activitySchedule;

        return $this;
    }

    public function getMaxQuota(): ?int
    {
        return $this->maxQuota;
    }

    public function setMaxQuota(?int $maxQuota): static
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
