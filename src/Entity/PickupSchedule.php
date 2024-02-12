<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PickupScheduleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PickupScheduleRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["startTime" => "ASC"],
        "normalization_context" => ["groups" => ["pickupScheduleReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "pickupSchedule"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class PickupSchedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['pickupScheduleReduced', 'pickupSchedule', 'pickup'])]
    private ?int $id = null;

    #[ORM\Column(length: 25)]
    #[Groups(['pickupScheduleReduced', 'pickupSchedule', 'pickup'])]
    private ?string $startTime = null;

    #[ORM\ManyToOne(inversedBy: 'schedules')]
    #[Groups(['pickupScheduleReduced', 'pickupSchedule'])]
    private ?Pickup $pickup = null;

    #[ORM\ManyToMany(targetEntity: Modality::class, mappedBy: 'pickupSchedules')]
    private Collection $modalities;

    public function __construct()
    {
        $this->modalities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartTime(): ?string
    {
        return $this->startTime;
    }

    public function setStartTime(string $startTime): static
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getPickup(): ?Pickup
    {
        return $this->pickup;
    }

    public function setPickup(?Pickup $pickup): static
    {
        $this->pickup = $pickup;

        return $this;
    }

    /**
     * @return Collection<int, Modality>
     */
    public function getModalities(): Collection
    {
        return $this->modalities;
    }

    public function addModality(Modality $modality): static
    {
        if (!$this->modalities->contains($modality)) {
            $this->modalities->add($modality);
            $modality->addPickupSchedule($this);
        }

        return $this;
    }

    public function removeModality(Modality $modality): static
    {
        if ($this->modalities->removeElement($modality)) {
            $modality->removePickupSchedule($this);
        }

        return $this;
    }
}
