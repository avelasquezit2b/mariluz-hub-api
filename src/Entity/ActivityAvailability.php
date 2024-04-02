<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
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
#[ApiFilter(SearchFilter::class, properties: ['activitySchedule.activitySeason.activityFee.activity' => 'exact'])]
#[ApiFilter(OrderFilter::class, properties: ['activitySchedule.startTime' => 'ASC'])]
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

    #[ORM\ManyToMany(targetEntity: ActivityBooking::class, mappedBy: 'activityAvailabilities')]
    private Collection $activityBookings;

    public function __construct()
    {
        $this->activityPrices = new ArrayCollection();
        $this->activityBookings = new ArrayCollection();
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

    /**
     * @return Collection<int, ActivityBooking>
     */
    public function getActivityBookings(): Collection
    {
        return $this->activityBookings;
    }

    public function addActivityBooking(ActivityBooking $activityBooking): static
    {
        if (!$this->activityBookings->contains($activityBooking)) {
            $this->activityBookings->add($activityBooking);
            $activityBooking->addActivityAvailability($this);
        }

        return $this;
    }

    public function removeActivityBooking(ActivityBooking $activityBooking): static
    {
        if ($this->activityBookings->removeElement($activityBooking)) {
            $activityBooking->removeActivityAvailability($this);
        }

        return $this;
    }
}
