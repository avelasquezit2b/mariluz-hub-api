<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\ModalityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ModalityRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["modalityReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "modality"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class Modality
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['modalityReduced', 'modality', 'activityReduced', 'activity'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['modalityReduced', 'modality', 'activityReduced', 'activity', 'activityAvailabilityReduced'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['modality', 'activity'])]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: ClientType::class, inversedBy: 'modalities')]
    #[Groups(['modalityReduced', 'modality', 'activity'])]
    private Collection $clientTypes;

    #[ORM\Column(length: 50)]
    #[Groups(['modalityReduced', 'modality', 'activity'])]
    private ?string $priceType = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $basePrice = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['modalityReduced', 'modality', 'activityReduced', 'activity'])]
    private ?string $minPax = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['modalityReduced', 'modality', 'activityReduced', 'activity'])]
    private ?string $maxPax = null;

    #[ORM\Column]
    #[Groups(['modalityReduced', 'modality', 'activityReduced', 'activity'])]
    private ?bool $hasPickup = null;

    #[ORM\ManyToMany(targetEntity: Pickup::class, inversedBy: 'modalities')]
    #[Groups(['modalityReduced', 'modality', 'activity'])]
    private Collection $pickups;

    #[ORM\OneToOne(mappedBy: 'modality', cascade: ['persist', 'remove'])]
    // #[Groups(['modalityReduced', 'modality', 'activityReduced', 'activity'])]
    // #[ApiSubresource]
    private ?ActivityFee $activityFee = null;

    #[ORM\ManyToOne(inversedBy: 'modalities')]
    private ?Activity $activity = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    #[Groups(['modalityReduced', 'modality', 'activityReduced', 'activity'])]
    private ?int $quota = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    #[Groups(['modalityReduced', 'modality', 'activityReduced', 'activity'])]
    private ?int $businessQuota = null;

    #[ORM\ManyToMany(targetEntity: Language::class, inversedBy: 'modalities')]
    #[Groups(['modalityReduced', 'modality', 'activityReduced', 'activity'])]
    private Collection $languages;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['modalityReduced', 'modality', 'activityReduced', 'activity'])]
    private ?string $price = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['modalityReduced', 'modality', 'activityReduced', 'activity'])]
    private ?string $duration = null;

    #[ORM\ManyToMany(targetEntity: PickupSchedule::class, inversedBy: 'modalities')]
    #[Groups(['modalityReduced', 'modality', 'activity'])]
    #[ORM\OrderBy(["startTime" => "ASC"])]
    private Collection $pickupSchedules;

    #[ORM\ManyToOne(inversedBy: 'modalities')]
    private ?Pack $pack = null;

    public function __construct()
    {
        $this->clientTypes = new ArrayCollection();
        $this->pickups = new ArrayCollection();
        $this->languages = new ArrayCollection();
        $this->pickupSchedules = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, ClientType>
     */
    public function getClientTypes(): Collection
    {
        return $this->clientTypes;
    }

    public function addClientType(ClientType $clientType): static
    {
        if (!$this->clientTypes->contains($clientType)) {
            $this->clientTypes->add($clientType);
        }

        return $this;
    }

    public function removeClientType(ClientType $clientType): static
    {
        $this->clientTypes->removeElement($clientType);

        return $this;
    }

    public function getPriceType(): ?string
    {
        return $this->priceType;
    }

    public function setPriceType(string $priceType): static
    {
        $this->priceType = $priceType;

        return $this;
    }

    public function getBasePrice(): ?string
    {
        return $this->basePrice;
    }

    public function setBasePrice(?string $basePrice): static
    {
        $this->basePrice = $basePrice;

        return $this;
    }

    public function getMinPax(): ?string
    {
        return $this->minPax;
    }

    public function setMinPax(?string $minPax): static
    {
        $this->minPax = $minPax;

        return $this;
    }

    public function getMaxPax(): ?string
    {
        return $this->maxPax;
    }

    public function setMaxPax(?string $maxPax): static
    {
        $this->maxPax = $maxPax;

        return $this;
    }

    public function isHasPickup(): ?bool
    {
        return $this->hasPickup;
    }

    public function setHasPickup(bool $hasPickup): static
    {
        $this->hasPickup = $hasPickup;

        return $this;
    }

    /**
     * @return Collection<int, Pickup>
     */
    public function getPickups(): Collection
    {
        return $this->pickups;
    }

    public function addPickup(Pickup $pickup): static
    {
        if (!$this->pickups->contains($pickup)) {
            $this->pickups->add($pickup);
        }

        return $this;
    }

    public function removePickup(Pickup $pickup): static
    {
        $this->pickups->removeElement($pickup);

        return $this;
    }

    public function getActivityFee(): ?ActivityFee
    {
        return $this->activityFee;
    }

    public function setActivityFee(?ActivityFee $activityFee): static
    {
        // unset the owning side of the relation if necessary
        if ($activityFee === null && $this->activityFee !== null) {
            $this->activityFee->setModality(null);
        }

        // set the owning side of the relation if necessary
        if ($activityFee !== null && $activityFee->getModality() !== $this) {
            $activityFee->setModality($this);
        }

        $this->activityFee = $activityFee;

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

    public function getQuota(): ?int
    {
        return $this->quota;
    }

    public function setQuota(?int $quota): static
    {
        $this->quota = $quota;

        return $this;
    }

    public function getBusinessQuota(): ?int
    {
        return $this->businessQuota;
    }

    public function setBusinessQuota(?int $businessQuota): static
    {
        $this->businessQuota = $businessQuota;

        return $this;
    }

    /**
     * @return Collection<int, Language>
     */
    public function getLanguages(): Collection
    {
        return $this->languages;
    }

    public function addLanguage(Language $language): static
    {
        if (!$this->languages->contains($language)) {
            $this->languages->add($language);
        }

        return $this;
    }

    public function removeLanguage(Language $language): static
    {
        $this->languages->removeElement($language);

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

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(?string $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return Collection<int, PickupSchedule>
     */
    public function getPickupSchedules(): Collection
    {
        return $this->pickupSchedules;
    }

    public function addPickupSchedule(PickupSchedule $pickupSchedule): static
    {
        if (!$this->pickupSchedules->contains($pickupSchedule)) {
            $this->pickupSchedules->add($pickupSchedule);
        }

        return $this;
    }

    public function removePickupSchedule(PickupSchedule $pickupSchedule): static
    {
        $this->pickupSchedules->removeElement($pickupSchedule);

        return $this;
    }

    public function getPack(): ?Pack
    {
        return $this->pack;
    }

    public function setPack(?Pack $pack): static
    {
        $this->pack = $pack;

        return $this;
    }
}
