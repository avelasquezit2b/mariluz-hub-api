<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\ModalityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModalityRepository::class)]
#[ApiResource]
class Modality
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: ClientType::class, inversedBy: 'modalities')]
    private Collection $clientTypes;

    #[ORM\Column(length: 50)]
    private ?string $priceType = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $basePrice = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $minPrice = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $maxPrice = null;

    #[ORM\Column]
    private ?bool $hasPickup = null;

    #[ORM\ManyToMany(targetEntity: Pickup::class, inversedBy: 'modalities')]
    private Collection $pickups;

    #[ORM\OneToOne(mappedBy: 'modality', cascade: ['persist', 'remove'])]
    #[ApiSubresource]
    private ?ActivityFee $activityFee = null;

    #[ORM\ManyToOne(inversedBy: 'modalities')]
    private ?Activity $activity = null;

    public function __construct()
    {
        $this->clientTypes = new ArrayCollection();
        $this->pickups = new ArrayCollection();
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

    public function getMinPrice(): ?string
    {
        return $this->minPrice;
    }

    public function setMinPrice(?string $minPrice): static
    {
        $this->minPrice = $minPrice;

        return $this;
    }

    public function getMaxPrice(): ?string
    {
        return $this->maxPrice;
    }

    public function setMaxPrice(?string $maxPrice): static
    {
        $this->maxPrice = $maxPrice;

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

}
