<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ClientTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientTypeRepository::class)]
#[ApiResource]
class ClientType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 25)]
    private ?string $code = null;

    #[ORM\Column(nullable: true)]
    private ?int $minAge = null;

    #[ORM\Column(nullable: true)]
    private ?int $maxAge = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $customTitle = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $types = null;

    #[ORM\ManyToMany(targetEntity: Modality::class, mappedBy: 'clientTypes')]
    private Collection $modalities;

    #[ORM\OneToMany(mappedBy: 'clientType', targetEntity: ActivityPrice::class)]
    private Collection $activityPrices;

    public function __construct()
    {
        $this->modalities = new ArrayCollection();
        $this->activityPrices = new ArrayCollection();
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getMinAge(): ?int
    {
        return $this->minAge;
    }

    public function setMinAge(?int $minAge): static
    {
        $this->minAge = $minAge;

        return $this;
    }

    public function getMaxAge(): ?int
    {
        return $this->maxAge;
    }

    public function setMaxAge(?int $maxAge): static
    {
        $this->maxAge = $maxAge;

        return $this;
    }

    public function getCustomTitle(): ?string
    {
        return $this->customTitle;
    }

    public function setCustomTitle(?string $customTitle): static
    {
        $this->customTitle = $customTitle;

        return $this;
    }

    public function getTypes(): ?array
    {
        return $this->types;
    }

    public function setTypes(?array $types): static
    {
        $this->types = $types;

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
            $modality->addClientType($this);
        }

        return $this;
    }

    public function removeModality(Modality $modality): static
    {
        if ($this->modalities->removeElement($modality)) {
            $modality->removeClientType($this);
        }

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
            $activityPrice->setClientType($this);
        }

        return $this;
    }

    public function removeActivityPrice(ActivityPrice $activityPrice): static
    {
        if ($this->activityPrices->removeElement($activityPrice)) {
            // set the owning side to null (unless already changed)
            if ($activityPrice->getClientType() === $this) {
                $activityPrice->setClientType(null);
            }
        }

        return $this;
    }
}
