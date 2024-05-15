<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\HotelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HotelRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "DESC"],
        "normalization_context" => ["groups" => ["hotelReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "hotel"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
#[ApiFilter(SearchFilter::class, properties: ['slug' => 'exact', 'id' => 'exact'])]
class Hotel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['hotelReduced', 'hotel', 'supplier', 'hotelAvailabilityReduced'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['hotelReduced', 'hotel', 'page', 'supplier', 'pack', 'productList', 'booking','bookingReduced'])]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['hotelReduced', 'hotel', 'page', 'productList'])]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'hotels')]
    #[Groups(['hotel', 'supplier', 'booking'])]
    private ?Supplier $supplier = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['hotelReduced', 'hotel', 'booking'])]
    private ?string $rating = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['hotel'])]
    private ?string $checkIn = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['hotel'])]
    private ?string $checkOut = null;

    #[ORM\ManyToOne(inversedBy: 'hotels', cascade: ['remove'])]
    #[Groups(['hotelReduced', 'hotel', 'page', 'productList', 'booking'])]
    private ?Location $location = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['hotelReduced', 'hotel'])]
    private ?string $address = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['hotel'])]
    private ?string $latitude = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['hotel'])]
    private ?string $longitude = null;

    #[ORM\ManyToMany(targetEntity: Zone::class, inversedBy: 'hotels')]
    #[Groups(['hotelReduced', 'hotel', 'page', 'productList', 'booking'])]
    private Collection $zones;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['hotelReduced', 'hotel', 'page', 'productList'])]
    private ?string $shortDescription = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['hotel'])]
    private ?string $extendedDescription = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['hotel'])]
    private ?string $highlights = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['hotel'])]
    private ?string $importantInformation = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['hotel'])]
    private ?string $cancelationConditions = null;

    #[ORM\OneToMany(mappedBy: 'hotel', targetEntity: RoomType::class, cascade: ['remove'])]
    #[ApiSubresource]
    #[Groups(['hotel'])]
    private Collection $roomTypes;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotel'])]
    private ?bool $isActive = null;

    #[ORM\OneToOne(inversedBy: 'hotel', cascade: ['remove'])]
    #[Groups(['hotel'])]
    private ?HotelServices $services = null;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'relatedHotels')]
    #[Groups(['hotel'])]
    private Collection $relatedHotels;

    #[ORM\OneToMany(mappedBy: 'hotel', targetEntity: MediaObject::class, cascade: ['remove'])]
    #[Groups(['hotelReduced', 'hotel', 'page', 'productList', 'booking'])]
    #[ORM\OrderBy(["position" => "ASC"])]
    private Collection $media;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotel'])]
    private ?int $adultsFrom = null;

    #[ORM\OneToMany(mappedBy: 'hotel', targetEntity: HotelFee::class, cascade: ['remove'])]
    // #[Groups(['hotel'])]
    #[ApiSubresource]
    private Collection $hotelFees;

    #[ORM\ManyToMany(targetEntity: ProductListModule::class, mappedBy: 'hotels')]
    private Collection $productListModules;

    #[ORM\OneToOne(inversedBy: 'hotel', cascade: ['persist', 'remove'])]
    #[Groups(['hotel', 'hotelReduced'])]
    private ?Seo $seo = null;

    #[ORM\ManyToMany(targetEntity: Modality::class, mappedBy: 'packHotels')]
    private Collection $packModalities;

    #[ORM\ManyToMany(targetEntity: Theme::class, mappedBy: 'hotels')]
    private Collection $themes;
    #[ORM\ManyToMany(targetEntity: ItineraryDay::class, mappedBy: 'hotels')]
    private Collection $itineraryDays;

    #[Groups(['hotelReduced', 'hotel', 'page', 'productList'])]
    private $price;

    #[ORM\OneToMany(mappedBy: 'hotel', targetEntity: PackPrice::class)]
    private Collection $packPrices;

    #[ORM\ManyToOne(inversedBy: 'hotels')]
    #[Groups(['hotelReduced', 'hotel', 'page', 'productList'])]
    private ?ProductTag $productTag = null;

    #[ORM\OneToMany(mappedBy: 'hotel', targetEntity: Voucher::class)]
    private Collection $vouchers;

    #[ORM\OneToMany(mappedBy: 'hotel', targetEntity: BookingLine::class)]
    private Collection $bookingLines;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotel'])]
    private ?bool $hasSupplierAvailability = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotel'])]
    private ?bool $hasTransferAvailability = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotel'])]
    private ?bool $isOnRequest = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotel'])]
    private ?bool $hasSendEmailClient = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotel'])]
    private ?bool $hasSendEmailAgency = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotel'])]
    private ?bool $hasSendEmailSupplier = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotel'])]
    private ?int $daysToPay = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotel'])]
    private ?int $daysToPayBeforeStay = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotel'])]
    private ?bool $isCredit = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['hotel'])]
    private ?string $bookingEmail = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['hotel'])]
    private ?string $internalNotes = null;

    #[ORM\OneToMany(mappedBy: 'hotel', targetEntity: ChannelHotel::class, cascade: ['remove'])]
    #[Groups(['hotel', 'hotelReduced'])]
    private Collection $channelHotels;

    public function __construct()
    {
        $this->zones = new ArrayCollection();
        $this->roomTypes = new ArrayCollection();
        $this->relatedHotels = new ArrayCollection();
        $this->media = new ArrayCollection();
        $this->hotelFees = new ArrayCollection();
        $this->productListModules = new ArrayCollection();
        $this->packModalities = new ArrayCollection();
        $this->themes = new ArrayCollection();
        $this->itineraryDays = new ArrayCollection();
        $this->packPrices = new ArrayCollection();
        $this->vouchers = new ArrayCollection();
        $this->bookingLines = new ArrayCollection();
        $this->channelHotels = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

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

    public function getRating(): ?string
    {
        return $this->rating;
    }

    public function setRating(?string $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getCheckIn(): ?string
    {
        return $this->checkIn;
    }

    public function setCheckIn(?string $checkIn): static
    {
        $this->checkIn = $checkIn;

        return $this;
    }

    public function getCheckOut(): ?string
    {
        return $this->checkOut;
    }

    public function setCheckOut(?string $checkOut): static
    {
        $this->checkOut = $checkOut;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

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

    /**
     * @return Collection<int, RoomType>
     */
    public function getRoomTypes(): Collection
    {
        return $this->roomTypes;
    }

    public function addRoomType(RoomType $roomType): static
    {
        if (!$this->roomTypes->contains($roomType)) {
            $this->roomTypes->add($roomType);
            $roomType->setHotel($this);
        }

        return $this;
    }

    public function removeRoomType(RoomType $roomType): static
    {
        if ($this->roomTypes->removeElement($roomType)) {
            // set the owning side to null (unless already changed)
            if ($roomType->getHotel() === $this) {
                $roomType->setHotel(null);
            }
        }

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getServices(): ?HotelServices
    {
        return $this->services;
    }

    public function setServices(?HotelServices $services): static
    {
        $this->services = $services;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getRelatedHotels(): Collection
    {
        return $this->relatedHotels;
    }

    public function addRelatedHotel(self $relatedHotel): static
    {
        if (!$this->relatedHotels->contains($relatedHotel)) {
            $this->relatedHotels->add($relatedHotel);
        }

        return $this;
    }

    public function removeRelatedHotel(self $relatedHotel): static
    {
        $this->relatedHotels->removeElement($relatedHotel);

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
            $medium->setHotel($this);
        }

        return $this;
    }

    public function removeMedium(MediaObject $medium): static
    {
        if ($this->media->removeElement($medium)) {
            // set the owning side to null (unless already changed)
            if ($medium->getHotel() === $this) {
                $medium->setHotel(null);
            }
        }

        return $this;
    }

    public function getAdultsFrom(): ?int
    {
        return $this->adultsFrom;
    }

    public function setAdultsFrom(?int $adultsFrom): static
    {
        $this->adultsFrom = $adultsFrom;

        return $this;
    }

    /**
     * @return Collection<int, HotelFee>
     */
    public function getHotelFees(): Collection
    {
        return $this->hotelFees;
    }

    public function addHotelFee(HotelFee $hotelFee): static
    {
        if (!$this->hotelFees->contains($hotelFee)) {
            $this->hotelFees->add($hotelFee);
            $hotelFee->setHotel($this);
        }

        return $this;
    }

    public function removeHotelFee(HotelFee $hotelFee): static
    {
        if ($this->hotelFees->removeElement($hotelFee)) {
            // set the owning side to null (unless already changed)
            if ($hotelFee->getHotel() === $this) {
                $hotelFee->setHotel(null);
            }
        }

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
            $productListModule->addHotel($this);
        }

        return $this;
    }

    public function removeProductListModule(ProductListModule $productListModule): static
    {
        if ($this->productListModules->removeElement($productListModule)) {
            $productListModule->removeHotel($this);
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

    /**
     * @return Collection<int, Modality>
     */
    public function getPackModalities(): Collection
    {
        return $this->packModalities;
    }

    public function addPackModality(Modality $modality): static
    {
        if (!$this->packModalities->contains($modality)) {
            $this->packModalities->add($modality);
            $modality->addPackHotel($this);
        }

        return $this;
    }

    public function removePackModality(Modality $modality): static
    {
        if ($this->packModalities->removeElement($modality)) {
            $modality->removePackHotel($this);
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
            $theme->addHotel($this);
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
            $itineraryDay->addHotel($this);
        }

        return $this;
    }

    public function removeTheme(Theme $theme): static
    {
        if ($this->themes->removeElement($theme)) {
            $theme->removeHotel($this);
        }
    }

    public function removeItineraryDay(ItineraryDay $itineraryDay): static
    {
        if ($this->itineraryDays->removeElement($itineraryDay)) {
            $itineraryDay->removeHotel($this);
        }

        return $this;
    }

    public function getPrice(): ?string
    {
        $price = null;
        foreach($this->getRoomTypes() as $roomType) {
            if (!$price) {
                $price = $roomType->getPrice();
            } else if ($price > $roomType->getPrice()) {
                $price = $roomType->getPrice();
            }
        }
        return $price;
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
            $packPrice->setHotel($this);
        }

        return $this;
    }

    public function removePackPrice(PackPrice $packPrice): static
    {
        if ($this->packPrices->removeElement($packPrice)) {
            // set the owning side to null (unless already changed)
            if ($packPrice->getHotel() === $this) {
                $packPrice->setHotel(null);
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
     * @return Collection<int, Voucher>
     */
    public function getVouchers(): Collection
    {
        return $this->vouchers;
    }

    public function addVoucher(Voucher $voucher): static
    {
        if (!$this->vouchers->contains($voucher)) {
            $this->vouchers->add($voucher);
            $voucher->setHotel($this);
        }

        return $this;
    }

    public function removeVoucher(Voucher $voucher): static
    {
        if ($this->vouchers->removeElement($voucher)) {
            // set the owning side to null (unless already changed)
            if ($voucher->getHotel() === $this) {
                $voucher->setHotel(null);
            }
        }

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
            $bookingLine->setHotel($this);
        }

        return $this;
    }

    public function removeBookingLine(BookingLine $bookingLine): static
    {
        if ($this->bookingLines->removeElement($bookingLine)) {
            // set the owning side to null (unless already changed)
            if ($bookingLine->getHotel() === $this) {
                $bookingLine->setHotel(null);
            }
        }

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

    public function getDaysToPay(): ?int
    {
        return $this->daysToPay;
    }

    public function setDaysToPay(?int $daysToPay): static
    {
        $this->daysToPay = $daysToPay;

        return $this;
    }

    public function getDaysToPayBeforeStay(): ?int
    {
        return $this->daysToPayBeforeStay;
    }

    public function setDaysToPayBeforeStay(?int $daysToPayBeforeStay): static
    {
        $this->daysToPayBeforeStay = $daysToPayBeforeStay;

        return $this;
    }

    public function isIsCredit(): ?bool
    {
        return $this->isCredit;
    }

    public function setIsCredit(?bool $isCredit): static
    {
        $this->isCredit = $isCredit;

        return $this;
    }

    public function getBookingEmail(): ?string
    {
        return $this->bookingEmail;
    }

    public function setBookingEmail(?string $bookingEmail): static
    {
        $this->bookingEmail = $bookingEmail;

        return $this;
    }

    public function getInternalNotes(): ?string
    {
        return $this->internalNotes;
    }

    public function setInternalNotes(?string $internalNotes): static
    {
        $this->internalNotes = $internalNotes;

        return $this;
    }

    /**
     * @return Collection<int, ChannelHotel>
     */
    public function getChannelHotels(): Collection
    {
        return $this->channelHotels;
    }

    public function addChannelHotel(ChannelHotel $channelHotel): static
    {
        if (!$this->channelHotels->contains($channelHotel)) {
            $this->channelHotels->add($channelHotel);
            $channelHotel->setHotel($this);
        }

        return $this;
    }

    public function removeChannelHotel(ChannelHotel $channelHotel): static
    {
        if ($this->channelHotels->removeElement($channelHotel)) {
            // set the owning side to null (unless already changed)
            if ($channelHotel->getHotel() === $this) {
                $channelHotel->setHotel(null);
            }
        }

        return $this;
    }

}
