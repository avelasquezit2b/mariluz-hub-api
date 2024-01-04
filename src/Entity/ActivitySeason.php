<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\ActivitySeasonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivitySeasonRepository::class)]
#[ApiResource]
class ActivitySeason
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $weekDays = [];

    #[ORM\Column]
    private ?bool $appliesToAllSchedules = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $ranges = [];

    #[ORM\ManyToOne(inversedBy: 'activitySeasons', cascade: ['persist', 'remove'])]
    private ?ActivityFee $activityFee = null;

    #[ORM\OneToMany(mappedBy: 'activitySeason', targetEntity: ActivitySchedule::class, cascade: ['persist', 'remove'])]
    #[ApiSubresource]
    private Collection $activitySchedules;

    public function __construct()
    {
        $this->activitySchedules = new ArrayCollection();
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

    public function getWeekDays(): array
    {
        return $this->weekDays;
    }

    public function setWeekDays(array $weekDays): static
    {
        $this->weekDays = $weekDays;

        return $this;
    }

    public function isAppliesToAllSchedules(): ?bool
    {
        return $this->appliesToAllSchedules;
    }

    public function setAppliesToAllSchedules(bool $appliesToAllSchedules): static
    {
        $this->appliesToAllSchedules = $appliesToAllSchedules;

        return $this;
    }

    public function getRanges(): array
    {
        return $this->ranges;
    }

    public function setRanges(array $ranges): static
    {
        $this->ranges = $ranges;

        return $this;
    }

    public function getActivityFee(): ?ActivityFee
    {
        return $this->activityFee;
    }

    public function setActivityFee(?ActivityFee $activityFee): static
    {
        $this->activityFee = $activityFee;

        return $this;
    }

    /**
     * @return Collection<int, ActivitySchedule>
     */
    public function getActivitySchedules(): Collection
    {
        return $this->activitySchedules;
    }

    public function addActivitySchedule(ActivitySchedule $activitySchedule): static
    {
        if (!$this->activitySchedules->contains($activitySchedule)) {
            $this->activitySchedules->add($activitySchedule);
            $activitySchedule->setActivitySeason($this);
        }

        return $this;
    }

    public function removeActivitySchedule(ActivitySchedule $activitySchedule): static
    {
        if ($this->activitySchedules->removeElement($activitySchedule)) {
            // set the owning side to null (unless already changed)
            if ($activitySchedule->getActivitySeason() === $this) {
                $activitySchedule->setActivitySeason(null);
            }
        }

        return $this;
    }
}
