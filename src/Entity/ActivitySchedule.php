<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\ActivityScheduleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ActivityScheduleRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["activityScheduleReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "activitySchedule"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class ActivitySchedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['activityScheduleReduced', 'activitySchedule', 'activityFeeReduced'])]
    private ?int $id = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['activityScheduleReduced', 'activitySchedule', 'activityFeeReduced', 'activityAvailabilityReduced', 'activityAvailability'])]
    private ?string $startTime = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['activityScheduleReduced', 'activitySchedule', 'activityFeeReduced', 'activityAvailabilityReduced', 'activityAvailability'])]
    private ?string $endTime = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['activityScheduleReduced', 'activitySchedule', 'activityFeeReduced'])]
    private ?string $duration = null;

    #[ORM\ManyToOne(inversedBy: 'activitySchedules')]
    #[Groups(['activityAvailabilityReduced', 'activityAvailability'])]
    private ?ActivitySeason $activitySeason = null;

    #[ORM\OneToMany(mappedBy: 'activitySchedule', targetEntity: ActivityPrice::class, cascade: ['remove'])]
    #[Groups(['activityScheduleReduced', 'activitySchedule', 'activityFeeReduced', 'activityAvailability'])]
    #[ORM\OrderBy(["price" => "DESC"])]
    #[ApiSubresource]
    private Collection $activityPrices;

    #[ORM\Column(type: Types::ARRAY)]
    #[Groups(['activityScheduleReduced', 'activitySchedule', 'activityFeeReduced'])]
    private array $weekDays = [];

    #[ORM\Column(nullable: true)]
    #[Groups(['activityScheduleReduced', 'activitySchedule', 'activityFeeReduced'])]
    private ?int $quota = null;

    #[ORM\OneToMany(mappedBy: 'activitySchedule', targetEntity: ActivityAvailability::class, cascade: ['remove'])]
    private Collection $activityAvailabilities;

    public function __construct()
    {
        $this->activityPrices = new ArrayCollection();
        $this->activityAvailabilities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartTime(): ?string
    {
        return $this->startTime;
    }

    public function setStartTime(?string $startTime): static
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?string
    {
        return $this->endTime;
    }

    public function setEndTime(?string $endTime): static
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(?string $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getActivitySeason(): ?ActivitySeason
    {
        return $this->activitySeason;
    }

    public function setActivitySeason(?ActivitySeason $activitySeason): static
    {
        $this->activitySeason = $activitySeason;

        return $this;
    }

    /**
     * @return Collection<int, ActivityPrice>
     */
    public function getActivityPrices(): Collection
    {
        return $this->activityPrices;
    }

    public function addActivityPrice(ActivityPrice $activityPrice): static
    {
        if (!$this->activityPrices->contains($activityPrice)) {
            $this->activityPrices->add($activityPrice);
            $activityPrice->setActivitySchedule($this);
        }

        return $this;
    }

    public function removeActivityPrice(ActivityPrice $activityPrice): static
    {
        if ($this->activityPrices->removeElement($activityPrice)) {
            // set the owning side to null (unless already changed)
            if ($activityPrice->getActivitySchedule() === $this) {
                $activityPrice->setActivitySchedule(null);
            }
        }

        return $this;
    }

    public function getWeekDays(): array
    {
        return $this->weekDays;
    }

    public function setWeekDays(array $weekDays): static
    {
        $this->weekDays = $weekDays;

        return $this;
    }

    public function getQuota(): ?int
    {
        return $this->quota;
    }

    public function setQuota(?int $quota): static
    {
        $this->quota = $quota;

        return $this;
    }

    /**
     * @return Collection<int, ActivityAvailability>
     */
    public function getActivityAvailabilities(): Collection
    {
        return $this->activityAvailabilities;
    }

    public function addActivityAvailability(ActivityAvailability $activityAvailability): static
    {
        if (!$this->activityAvailabilities->contains($activityAvailability)) {
            $this->activityAvailabilities->add($activityAvailability);
            $activityAvailability->setActivitySchedule($this);
        }

        return $this;
    }

    public function removeActivityAvailability(ActivityAvailability $activityAvailability): static
    {
        if ($this->activityAvailabilities->removeElement($activityAvailability)) {
            // set the owning side to null (unless already changed)
            if ($activityAvailability->getActivitySchedule() === $this) {
                $activityAvailability->setActivitySchedule(null);
            }
        }

        return $this;
    }
}
