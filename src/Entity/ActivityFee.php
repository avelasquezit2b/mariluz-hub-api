<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\ActivityFeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityFeeRepository::class)]
#[ApiResource]
class ActivityFee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $duration = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $price = null;

    #[ORM\Column(nullable: true)]
    private ?bool $hasConsolidatedQuota = null;

    #[ORM\Column(nullable: true)]
    private ?bool $hasOnRequest = null;

    #[ORM\OneToOne(inversedBy: 'activityFee', cascade: ['persist', 'remove'])]
    private ?Modality $modality = null;

    #[ORM\ManyToOne(inversedBy: 'activityFees')]
    private ?Activity $activity = null;

    #[ORM\OneToMany(mappedBy: 'activityFee', targetEntity: ActivitySeason::class, cascade: ['persist', 'remove'])]
    #[ApiSubresource]
    private Collection $activitySeasons;

    public function __construct()
    {
        $this->activitySeasons = new ArrayCollection();
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

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(?string $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function isHasConsolidatedQuota(): ?bool
    {
        return $this->hasConsolidatedQuota;
    }

    public function setHasConsolidatedQuota(?bool $hasConsolidatedQuota): static
    {
        $this->hasConsolidatedQuota = $hasConsolidatedQuota;

        return $this;
    }

    public function isHasOnRequest(): ?bool
    {
        return $this->hasOnRequest;
    }

    public function setHasOnRequest(?bool $hasOnRequest): static
    {
        $this->hasOnRequest = $hasOnRequest;

        return $this;
    }

    public function getModality(): ?Modality
    {
        return $this->modality;
    }

    public function setModality(?Modality $modality): static
    {
        $this->modality = $modality;

        return $this;
    }

    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    public function setActivity(?Activity $activity): static
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * @return Collection<int, ActivitySeason>
     */
    public function getActivitySeasons(): Collection
    {
        return $this->activitySeasons;
    }

    public function addActivitySeason(ActivitySeason $activitySeason): static
    {
        if (!$this->activitySeasons->contains($activitySeason)) {
            $this->activitySeasons->add($activitySeason);
            $activitySeason->setActivityFee($this);
        }

        return $this;
    }

    public function removeActivitySeason(ActivitySeason $activitySeason): static
    {
        if ($this->activitySeasons->removeElement($activitySeason)) {
            // set the owning side to null (unless already changed)
            if ($activitySeason->getActivityFee() === $this) {
                $activitySeason->setActivityFee(null);
            }
        }

        return $this;
    }
}
