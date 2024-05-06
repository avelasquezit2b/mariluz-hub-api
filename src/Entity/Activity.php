<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\ActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityRepository::class)]
#[ApiResource(
    paginationEnabled: false,
    attributes: [
        "order" => ["id" => "DESC"],
        "normalization_context" => ["groups" => ["activityReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "activity"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'title' => 'partial', 'slug' => 'exact'])]
class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['activityReduced', 'activity', 'supplierReduced', 'supplier', 'activityAvailabilityReduced'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['activityReduced', 'activity', 'supplierReduced', 'supplier', 'pack', 'page', 'productList', 'booking'])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Groups(['activityReduced', 'activity', 'page', 'productList'])]
    private ?string $slug = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['activityReduced', 'activity', 'page', 'productList'])]
    private ?string $shortDescription = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['activity'])]
    private ?string $extendedDescription = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['activity'])]
    private ?string $highlights = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['activity'])]
    private ?string $includes = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['activity'])]
    private ?string $notIncludes = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['activity'])]
    private ?string $carry = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['activity'])]
    private ?string $importantInformation = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['activity'])]
    private ?string $cancelationConditions = null;

    #[ORM\ManyToOne(inversedBy: 'activities')]
    #[Groups(['activity', 'supplier'])]
    private ?Supplier $supplier = null;

    #[ORM\Column]
    #[Groups(['activity'])]
    private ?bool $isActive = false;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'activities')]
    #[Groups(['activityReduced', 'activity'])]
    private Collection $categories;

    #[ORM\ManyToMany(targetEntity: Subcategory::class, inversedBy: 'activities')]
    #[Groups(['activityReduced', 'activity'])]
    private Collection $subCategories;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'relatedActivities')]
    #[Groups(['activity'])]
    private Collection $relatedActivities;

    #[ORM\OneToMany(mappedBy: 'activity', targetEntity: Modality::class, cascade: ['persist', 'remove'])]
    #[Groups(['activity'])]
    private Collection $modalities;

    #[ORM\OneToMany(mappedBy: 'activity', targetEntity: ActivityFee::class)]
    // #[Groups(['activity'])]
    #[ApiSubresource]
    private Collection $activityFees;

    #[ORM\OneToMany(mappedBy: 'activity', targetEntity: MediaObject::class, cascade: ['remove'])]
    #[Groups(['activityReduced', 'activity', 'page', 'productList', 'booking'])]
    #[ORM\OrderBy(["position" => "ASC"])]
    #[ApiSubresource]
    private Collection $media;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['activity'])]
    private ?string $releaseHour = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['activity'])]
    private ?bool $hasSupplierAvailability = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['activity'])]
    private ?bool $hasTransferAvailability = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['activity'])]
    private ?bool $isOnRequest = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['activity'])]
    private ?bool $hasSendEmailClient = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['activity'])]
    private ?bool $hasSendEmailAgency = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['activity'])]
    private ?bool $hasSendEmailSupplier = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['activity'])]
    private ?string $tripadvisorId = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['activity'])]
    private ?string $getyourguideId = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['activity'])]
    private ?string $vennturId = null;

    #[ORM\ManyToOne(inversedBy: 'activities', cascade: ['persist'])]
    #[Groups(['activityReduced', 'activity', 'page', 'productList', 'booking'])]
    private ?Location $location = null;

    #[ORM\ManyToMany(targetEntity: Zone::class, inversedBy: 'activities')]
    #[Groups(['activityReduced', 'activity', 'page', 'productList', 'booking'])]
    private Collection $zones;

    #[ORM\ManyToMany(targetEntity: ProductListModule::class, mappedBy: 'activities')]
    private Collection $productListModules;

    #[ORM\ManyToMany(targetEntity: Modality::class, mappedBy: 'packActivities')]
    private Collection $packModalities;

    #[ORM\ManyToMany(targetEntity: Theme::class, mappedBy: 'activities')]
    private Collection $themes;
    #[ORM\ManyToMany(targetEntity: ItineraryDay::class, mappedBy: 'activities')]
    private Collection $itineraryDays;

    #[Groups(['activityReduced', 'activity', 'page', 'productList'])]
    private $price;

    #[Groups(['activityReduced', 'activity', 'page', 'productList'])]
    private $languages;

    #[Groups(['activityReduced', 'activity', 'page', 'productList'])]
    private $duration;

    #[ORM\OneToMany(mappedBy: 'activity', targetEntity: PackPrice::class)]
    private Collection $packPrices;

    #[ORM\ManyToOne(inversedBy: 'activities')]
    #[Groups(['activityReduced', 'activity', 'page', 'productList'])]
    private ?ProductTag $productTag = null;

    #[ORM\OneToMany(mappedBy: 'activity', targetEntity: BookingLine::class)]
    private Collection $bookingLines;

    #[ORM\OneToOne(inversedBy: 'activity', cascade: ['persist', 'remove'])]
    #[Groups(['activity', 'activityReduced'])]
    private ?Seo $seo = null;

    // #[ORM\Column(length: 25, nullable: true)]
    // private ?string $duration = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['activityReduced', 'activity', 'page', 'productList'])]
    private ?string $tiqetsId = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['activityReduced', 'activity', 'page', 'productList'])]
    private ?string $latitude = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['activityReduced', 'activity', 'page', 'productList'])]
    private ?string $longitude = null;

    // #[ORM\Column(length: 25, nullable: true)]
    // private ?string $price = null;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->subCategories = new ArrayCollection();
        $this->relatedActivities = new ArrayCollection();
        $this->modalities = new ArrayCollection();
        $this->activityFees = new ArrayCollection();
        $this->media = new ArrayCollection();
        $this->zones = new ArrayCollection();
        $this->productListModules = new ArrayCollection();
        $this->packModalities = new ArrayCollection();
        $this->themes = new ArrayCollection();
        $this->itineraryDays = new ArrayCollection();
        $this->packPrices = new ArrayCollection();
        $this->bookingLines = new ArrayCollection();
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

    public function isIsOnRequest(): ?bool
    {
        return $this->isOnRequest;
    }

    public function setIsOnRequest(?bool $isOnRequest): static
    {
        $this->isOnRequest = $isOnRequest;

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

    /**
     * @return Collection<int, ProductListModule>
     */
    public function getProductListModules(): Collection
    {
        return $this->productListModules;
    }

    public function addProductListModule(ProductListModule $productListModule): static
    {
        if (!$this->productListModules->contains($productListModule)) {
            $this->productListModules->add($productListModule);
            $productListModule->addActivity($this);
        }

        return $this;
    }

    public function removeProductListModule(ProductListModule $productListModule): static
    {
        if ($this->productListModules->removeElement($productListModule)) {
            $productListModule->removeActivity($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Modality>
     */
    public function getPackModalities(): Collection
    {
        return $this->packModalities;
    }

    public function addPackModality(Modality $packModality): static
    {
        if (!$this->packModalities->contains($packModality)) {
            $this->packModalities->add($packModality);
            $packModality->addPackActivity($this);
        }

        return $this;
    }

    public function removePackModality(Modality $packModality): static
    {
        if ($this->packModalities->removeElement($packModality)) {
            $packModality->removePackActivity($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Theme>
     */
    public function getThemes(): Collection
    {
        return $this->themes;
    }

    public function addTheme(Theme $theme): static
    {
        if (!$this->themes->contains($theme)) {
            $this->themes->add($theme);
            $theme->addActivity($this);
        }
    }

    /**
     * @return Collection<int, ItineraryDay>
     */
    public function getItineraryDays(): Collection
    {
        return $this->itineraryDays;
    }

    public function addItineraryDay(ItineraryDay $itineraryDay): static
    {
        if (!$this->itineraryDays->contains($itineraryDay)) {
            $this->itineraryDays->add($itineraryDay);
            $itineraryDay->addActivity($this);
        }

        return $this;
    }

    public function removeTheme(Theme $theme): static
    {
        if ($this->themes->removeElement($theme)) {
            $theme->removeActivity($this);
        }
    }

    public function removeItineraryDay(ItineraryDay $itineraryDay): static
    {
        if ($this->itineraryDays->removeElement($itineraryDay)) {
            $itineraryDay->removeActivity($this);
        }

        return $this;
    }

    public function getPrice(): ?string
    {
        $price = null;
        foreach ($this->getModalities() as $modality) {
            if (!$price) {
                $price = $modality->getPrice();
            } else if ($price > $modality->getPrice()) {
                $price = $modality->getPrice();
            }
        }
        return $price;
    }

    public function getLanguages(): ?array
    {
        $languages = [];
        foreach ($this->getModalities() as $modality) {
            foreach ($modality->getLanguages() as $language) {
                if (!in_array($language, $languages)) {
                    array_push($languages, $language->getName());
                }
            }
        }
        return $languages;
    }

    public function getDuration(): ?string
    {
        $duration = '';
        foreach ($this->getModalities() as $modality) {
            if ($modality->getDuration()) {
                $duration = $modality->getDuration();
            }
        }

        return $duration;
    }

    /**
     * @return Collection<int, PackPrice>
     */
    public function getPackPrices(): Collection
    {
        return $this->packPrices;
    }

    public function addPackPrice(PackPrice $packPrice): static
    {
        if (!$this->packPrices->contains($packPrice)) {
            $this->packPrices->add($packPrice);
            $packPrice->setActivity($this);
        }

        return $this;
    }

    public function removePackPrice(PackPrice $packPrice): static
    {
        if ($this->packPrices->removeElement($packPrice)) {
            // set the owning side to null (unless already changed)
            if ($packPrice->getActivity() === $this) {
                $packPrice->setActivity(null);
            }
        }

        return $this;
    }

    public function getProductTag(): ?ProductTag
    {
        return $this->productTag;
    }

    public function setProductTag(?ProductTag $productTag): static
    {
        $this->productTag = $productTag;

        return $this;
    }

    /**
     * @return Collection<int, BookingLine>
     */
    public function getBookingLines(): Collection
    {
        return $this->bookingLines;
    }

    public function addBookingLine(BookingLine $bookingLine): static
    {
        if (!$this->bookingLines->contains($bookingLine)) {
            $this->bookingLines->add($bookingLine);
            $bookingLine->setActivity($this);
        }

        return $this;
    }

    public function removeBookingLine(BookingLine $bookingLine): static
    {
        if ($this->bookingLines->removeElement($bookingLine)) {
            // set the owning side to null (unless already changed)
            if ($bookingLine->getActivity() === $this) {
                $bookingLine->setActivity(null);
            }
        }

        return $this;
    }

    public function getSeo(): ?Seo
    {
        return $this->seo;
    }

    public function setSeo(?Seo $seo): static
    {
        $this->seo = $seo;

        return $this;
    }

    // public function getDuration(): ?string
    // {
    //     return $this->duration;
    // }

    // public function setDuration(?string $duration): static
    // {
    //     $this->duration = $duration;

    //     return $this;
    // }

    public function getTiqetsId(): ?string
    {
        return $this->tiqetsId;
    }

    public function setTiqetsId(?string $tiqetsId): static
    {
        $this->tiqetsId = $tiqetsId;

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

    // public function setPrice(?string $price): static
    // {
    //     $this->price = $price;

    //     return $this;
    // }
}
