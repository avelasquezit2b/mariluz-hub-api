<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\ActivitySeasonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ActivitySeasonRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["activitySeasonReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "activitySeason"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class ActivitySeason
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['activitySeasonReduced', 'activitySeason', 'activityFeeReduced'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['activitySeasonReduced', 'activitySeason', 'activityFeeReduced'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::ARRAY)]
    #[Groups(['activitySeasonReduced', 'activitySeason', 'activityFeeReduced'])]
    private array $weekDays = [];

    #[ORM\Column]
    #[Groups(['activitySeasonReduced', 'activitySeason', 'activityFeeReduced'])]
    private ?bool $appliesToAllSchedules = null;

    #[ORM\Column(type: Types::ARRAY)]
    #[Groups(['activitySeasonReduced', 'activitySeason', 'activityFeeReduced'])]
    private array $ranges = [];

    #[ORM\ManyToOne(inversedBy: 'activitySeasons', cascade: ['persist', 'remove'])]
    #[Groups(['activityAvailabilityReduced', 'activityAvailability'])]
    private ?ActivityFee $activityFee = null;

    #[ORM\OneToMany(mappedBy: 'activitySeason', targetEntity: ActivitySchedule::class, cascade: ['persist', 'remove'])]
    #[Groups(['activitySeasonReduced', 'activitySeason', 'activityFeeReduced'])]
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
