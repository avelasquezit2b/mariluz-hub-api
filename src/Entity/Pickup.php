<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use App\Repository\PickupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PickupRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["pickupReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "pickup"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
#[ApiFilter(BooleanFilter::class, properties: ['isMeetingPoint'])]
class Pickup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['pickupReduced', 'pickup'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['pickupReduced', 'pickup', 'pickupScheduleReduced'])]
    private ?string $title = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['pickupReduced', 'pickup'])]
    private ?string $latitude = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['pickupReduced', 'pickup'])]
    private ?string $longitude = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['pickupReduced', 'pickup'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['pickupReduced', 'pickup'])]
    private ?bool $isMeetingPoint = null;

    #[ORM\ManyToMany(targetEntity: Modality::class, mappedBy: 'pickups')]
    #[Groups(['pickupReduced', 'pickup'])]
    private Collection $modalities;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['pickupReduced', 'pickup'])]
    private ?string $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['pickupReduced', 'pickup'])]
    private ?string $imageUrl = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['pickupReduced', 'pickup'])]
    private ?string $mapsUrl = null;

    #[ORM\OneToMany(mappedBy: 'pickup', targetEntity: PickupSchedule::class)]
    #[Groups(['pickupReduced', 'pickup'])]
    #[ORM\OrderBy(["startTime" => "ASC"])]
    private Collection $schedules;

    public function __construct()
    {
        $this->modalities = new ArrayCollection();
        $this->schedules = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isIsMeetingPoint(): ?bool
    {
        return $this->isMeetingPoint;
    }

    public function setIsMeetingPoint(bool $isMeetingPoint): static
    {
        $this->isMeetingPoint = $isMeetingPoint;

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
            $modality->addPickup($this);
        }

        return $this;
    }

    public function removeModality(Modality $modality): static
    {
        if ($this->modalities->removeElement($modality)) {
            $modality->removePickup($this);
        }

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getMapsUrl(): ?string
    {
        return $this->mapsUrl;
    }

    public function setMapsUrl(?string $mapsUrl): static
    {
        $this->mapsUrl = $mapsUrl;

        return $this;
    }

    /**
     * @return Collection<int, PickupSchedule>
     */
    public function getSchedules(): Collection
    {
        return $this->schedules;
    }

    public function addSchedule(PickupSchedule $schedule): static
    {
        if (!$this->schedules->contains($schedule)) {
            $this->schedules->add($schedule);
            $schedule->setPickup($this);
        }

        return $this;
    }

    public function removeSchedule(PickupSchedule $schedule): static
    {
        if ($this->schedules->removeElement($schedule)) {
            // set the owning side to null (unless already changed)
            if ($schedule->getPickup() === $this) {
                $schedule->setPickup(null);
            }
        }

        return $this;
    }
}
