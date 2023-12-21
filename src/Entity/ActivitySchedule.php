<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\ActivityScheduleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityScheduleRepository::class)]
#[ApiResource]
class ActivitySchedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $startTime = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $endTime = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $duration = null;

    #[ORM\ManyToOne(inversedBy: 'activitySchedules')]
    private ?ActivitySeason $activitySeason = null;

    #[ORM\OneToMany(mappedBy: 'activitySchedule', targetEntity: ActivityPrice::class)]
    #[ApiSubresource]
    private Collection $activityPrices;

    #[ORM\Column(type: Types::ARRAY)]
    private array $weekDays = [];

    public function __construct()
    {
        $this->activityPrices = new ArrayCollection();
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
}
