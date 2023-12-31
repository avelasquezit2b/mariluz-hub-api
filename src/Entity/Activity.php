<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\ActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get",
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'partial', 'title' => 'partial'])]
class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $shortDescription = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $extendedDescription = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $highlights = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $includes = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notIncludes = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $carry = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $importantInformation = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $cancelationConditions = null;

    #[ORM\ManyToOne(inversedBy: 'activities')]
    private ?Supplier $supplier = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'activities')]
    private Collection $categories;

    #[ORM\ManyToMany(targetEntity: Subcategory::class, inversedBy: 'activities')]
    private Collection $subCategories;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'relatedActivities')]
    private Collection $relatedActivities;

    #[ORM\OneToMany(mappedBy: 'activity', targetEntity: Modality::class, cascade: ['persist', 'remove'])]
    #[ApiSubresource]
    private Collection $modalities;

    #[ORM\OneToMany(mappedBy: 'activity', targetEntity: ActivityFee::class)]
    private Collection $activityFees;

    #[ORM\OneToMany(mappedBy: 'activity', targetEntity: MediaObject::class)]
    #[ApiSubresource]
    private Collection $media;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $releaseHour = null;

    #[ORM\Column(nullable: true)]
    private ?bool $hasSupplierAvailability = null;

    #[ORM\Column(nullable: true)]
    private ?bool $hasTransferAvailability = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isUnderPetition = null;

    #[ORM\Column(nullable: true)]
    private ?bool $hasSendEmailClient = null;

    #[ORM\Column(nullable: true)]
    private ?bool $hasSendEmailAgency = null;

    #[ORM\Column(nullable: true)]
    private ?bool $hasSendEmailSupplier = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $tripadvisorId = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $getyourguideId = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $vennturId = null;

    #[ORM\ManyToOne(inversedBy: 'activities', cascade: ['persist', 'remove'])]
    private ?Location $location = null;

    #[ORM\ManyToMany(targetEntity: Zone::class, inversedBy: 'activities')]
    private Collection $zones;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->subCategories = new ArrayCollection();
        $this->relatedActivities = new ArrayCollection();
        $this->modalities = new ArrayCollection();
        $this->activityFees = new ArrayCollection();
        $this->media = new ArrayCollection();
        $this->zones = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(?string $shortDescription): static
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getExtendedDescription(): ?string
    {
        return $this->extendedDescription;
    }

    public function setExtendedDescription(?string $extendedDescription): static
    {
        $this->extendedDescription = $extendedDescription;

        return $this;
    }

    public function getHighlights(): ?string
    {
        return $this->highlights;
    }

    public function setHighlights(?string $highlights): static
    {
        $this->highlights = $highlights;

        return $this;
    }

    public function getIncludes(): ?string
    {
        return $this->includes;
    }

    public function setIncludes(?string $includes): static
    {
        $this->includes = $includes;

        return $this;
    }

    public function getNotIncludes(): ?string
    {
        return $this->notIncludes;
    }

    public function setNotIncludes(?string $notIncludes): static
    {
        $this->notIncludes = $notIncludes;

        return $this;
    }

    public function getCarry(): ?string
    {
        return $this->carry;
    }

    public function setCarry(?string $carry): static
    {
        $this->carry = $carry;

        return $this;
    }

    public function getImportantInformation(): ?string
    {
        return $this->importantInformation;
    }

    public function setImportantInformation(?string $importantInformation): static
    {
        $this->importantInformation = $importantInformation;

        return $this;
    }

    public function getCancelationConditions(): ?string
    {
        return $this->cancelationConditions;
    }

    public function setCancelationConditions(?string $cancelationConditions): static
    {
        $this->cancelationConditions = $cancelationConditions;

        return $this;
    }

    public function getSupplier(): ?Supplier
    {
        return $this->supplier;
    }

    public function setSupplier(?Supplier $supplier): static
    {
        $this->supplier = $supplier;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, Subcategory>
     */
    public function getSubCategories(): Collection
    {
        return $this->subCategories;
    }

    public function addSubCategory(Subcategory $subCategory): static
    {
        if (!$this->subCategories->contains($subCategory)) {
            $this->subCategories->add($subCategory);
        }

        return $this;
    }

    public function removeSubCategory(Subcategory $subCategory): static
    {
        $this->subCategories->removeElement($subCategory);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getRelatedActivities(): Collection
    {
        return $this->relatedActivities;
    }

    public function addRelatedActivity(self $relatedActivity): static
    {
        if (!$this->relatedActivities->contains($relatedActivity)) {
            $this->relatedActivities->add($relatedActivity);
        }

        return $this;
    }

    public function removeRelatedActivity(self $relatedActivity): static
    {
        $this->relatedActivities->removeElement($relatedActivity);

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
            $modality->setActivity($this);
        }

        return $this;
    }

    public function removeModality(Modality $modality): static
    {
        if ($this->modalities->removeElement($modality)) {
            // set the owning side to null (unless already changed)
            if ($modality->getActivity() === $this) {
                $modality->setActivity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ActivityFee>
     */
    public function getActivityFees(): Collection
    {
        return $this->activityFees;
    }

    public function addActivityFee(ActivityFee $activityFee): static
    {
        if (!$this->activityFees->contains($activityFee)) {
            $this->activityFees->add($activityFee);
            $activityFee->setActivity($this);
        }

        return $this;
    }

    public function removeActivityFee(ActivityFee $activityFee): static
    {
        if ($this->activityFees->removeElement($activityFee)) {
            // set the owning side to null (unless already changed)
            if ($activityFee->getActivity() === $this) {
                $activityFee->setActivity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MediaObject>
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(MediaObject $medium): static
    {
        if (!$this->media->contains($medium)) {
            $this->media->add($medium);
            $medium->setActivity($this);
        }

        return $this;
    }

    public function removeMedium(MediaObject $medium): static
    {
        if ($this->media->removeElement($medium)) {
            // set the owning side to null (unless already changed)
            if ($medium->getActivity() === $this) {
                $medium->setActivity(null);
            }
        }

        return $this;
    }

    public function getReleaseHour(): ?string
    {
        return $this->releaseHour;
    }

    public function setReleaseHour(?string $releaseHour): static
    {
        $this->releaseHour = $releaseHour;

        return $this;
    }

    public function isHasSupplierAvailability(): ?bool
    {
        return $this->hasSupplierAvailability;
    }

    public function setHasSupplierAvailability(?bool $hasSupplierAvailability): static
    {
        $this->hasSupplierAvailability = $hasSupplierAvailability;

        return $this;
    }

    public function isHasTransferAvailability(): ?bool
    {
        return $this->hasTransferAvailability;
    }

    public function setHasTransferAvailability(?bool $hasTransferAvailability): static
    {
        $this->hasTransferAvailability = $hasTransferAvailability;

        return $this;
    }

    public function isIsUnderPetition(): ?bool
    {
        return $this->isUnderPetition;
    }

    public function setIsUnderPetition(?bool $isUnderPetition): static
    {
        $this->isUnderPetition = $isUnderPetition;

        return $this;
    }

    public function isHasSendEmailClient(): ?bool
    {
        return $this->hasSendEmailClient;
    }

    public function setHasSendEmailClient(?bool $hasSendEmailClient): static
    {
        $this->hasSendEmailClient = $hasSendEmailClient;

        return $this;
    }

    public function isHasSendEmailAgency(): ?bool
    {
        return $this->hasSendEmailAgency;
    }

    public function setHasSendEmailAgency(?bool $hasSendEmailAgency): static
    {
        $this->hasSendEmailAgency = $hasSendEmailAgency;

        return $this;
    }

    public function isHasSendEmailSupplier(): ?bool
    {
        return $this->hasSendEmailSupplier;
    }

    public function setHasSendEmailSupplier(?bool $hasSendEmailSupplier): static
    {
        $this->hasSendEmailSupplier = $hasSendEmailSupplier;

        return $this;
    }

    public function getTripadvisorId(): ?string
    {
        return $this->tripadvisorId;
    }

    public function setTripadvisorId(?string $tripadvisorId): static
    {
        $this->tripadvisorId = $tripadvisorId;

        return $this;
    }

    public function getGetyourguideId(): ?string
    {
        return $this->getyourguideId;
    }

    public function setGetyourguideId(?string $getyourguideId): static
    {
        $this->getyourguideId = $getyourguideId;

        return $this;
    }

    public function getVennturId(): ?string
    {
        return $this->vennturId;
    }

    public function setVennturId(?string $vennturId): static
    {
        $this->vennturId = $vennturId;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): static
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return Collection<int, Zone>
     */
    public function getZones(): Collection
    {
        return $this->zones;
    }

    public function addZone(Zone $zone): static
    {
        if (!$this->zones->contains($zone)) {
            $this->zones->add($zone);
        }

        return $this;
    }

    public function removeZone(Zone $zone): static
    {
        $this->zones->removeElement($zone);

        return $this;
    }
}
