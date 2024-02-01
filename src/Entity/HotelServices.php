<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\HotelServicesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: HotelServicesRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["hotelServicesReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "hotelServices"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class HotelServices
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: PensionType::class, inversedBy: 'hotelServices')]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private Collection $pensionTypes;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?string $breakfastStartTime = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?string $breakfastEndTime = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?string $lunchStartTime = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?string $lunchEndTime = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?string $brunchStartTime = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?string $brunchEndTime = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?string $dinnerStartTime = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?string $dinnerEndTime = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?string $barStartTime = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?string $barEndTime = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?string $generalObservations = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasFullTimeReception = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasHeating = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasAirConditioner = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasWiFi = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasElevator = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $isAdaptedReducedMobility = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $isPetsAllowed = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasCradles = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasParking = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasSpaAccess = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasGym = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasRestaurant = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasBar = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasPoolBar = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasBuffetBreakfast = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasRoomService = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasBuffetMeal = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasBuffetDinner = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasGlutenFreeFoods = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasThemedRestaurants = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasChillOut = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasBeachClub = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasMassages = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasSauna = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasJacuzzi = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasTurkishBath = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasGameRoom = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasHairdresser = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasStoresAtHotel = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasSunLoungers = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasSuperMarket = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasTerrace = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasTennisCourt = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasPaddleCourt = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasSoccerField = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasBasketballCourt = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasOutdoorSwimmingPool = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasChildrenPool = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasChildrenClub = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasChildrenAnimation = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasAdultAnimation = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasRoomTv = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasRoomPhone = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasRoomTerrace = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasRoomHairDryer = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasRoomHeating = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasRoomAirConditioner = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasRoomSafeDepositBox = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasRoomDesk = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasRoomWiFi = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasRoomWC = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasRoomShower = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasRoomBathtub = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['hotelServicesReduced', 'hotelServices', 'hotel'])]
    private ?bool $hasRoomBidet = true;

    #[ORM\OneToOne(mappedBy: 'services', cascade: ['persist', 'remove'])]
    #[Groups(['hotelServicesReduced', 'hotelServices'])]
    private ?Hotel $hotel = null;

    public function __construct()
    {
        $this->pensionTypes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, PensionType>
     */
    public function getPensionTypes(): Collection
    {
        return $this->pensionTypes;
    }

    public function addPensionType(PensionType $pensionType): static
    {
        if (!$this->pensionTypes->contains($pensionType)) {
            $this->pensionTypes->add($pensionType);
        }

        return $this;
    }

    public function removePensionType(PensionType $pensionType): static
    {
        $this->pensionTypes->removeElement($pensionType);

        return $this;
    }

    public function getBreakfastStartTime(): ?string
    {
        return $this->breakfastStartTime;
    }

    public function setBreakfastStartTime(?string $breakfastStartTime): static
    {
        $this->breakfastStartTime = $breakfastStartTime;

        return $this;
    }

    public function getBreakfastEndTime(): ?string
    {
        return $this->breakfastEndTime;
    }

    public function setBreakfastEndTime(?string $breakfastEndTime): static
    {
        $this->breakfastEndTime = $breakfastEndTime;

        return $this;
    }

    public function getLunchStartTime(): ?string
    {
        return $this->lunchStartTime;
    }

    public function setLunchStartTime(?string $lunchStartTime): static
    {
        $this->lunchStartTime = $lunchStartTime;

        return $this;
    }

    public function getLunchEndTime(): ?string
    {
        return $this->lunchEndTime;
    }

    public function setLunchEndTime(?string $lunchEndTime): static
    {
        $this->lunchEndTime = $lunchEndTime;

        return $this;
    }

    public function getBrunchStartTime(): ?string
    {
        return $this->brunchStartTime;
    }

    public function setBrunchStartTime(?string $brunchStartTime): static
    {
        $this->brunchStartTime = $brunchStartTime;

        return $this;
    }

    public function getBrunchEndTime(): ?string
    {
        return $this->brunchEndTime;
    }

    public function setBrunchEndTime(?string $brunchEndTime): static
    {
        $this->brunchEndTime = $brunchEndTime;

        return $this;
    }

    public function getDinnerStartTime(): ?string
    {
        return $this->dinnerStartTime;
    }

    public function setDinnerStartTime(?string $dinnerStartTime): static
    {
        $this->dinnerStartTime = $dinnerStartTime;

        return $this;
    }

    public function getDinnerEndTime(): ?string
    {
        return $this->dinnerEndTime;
    }

    public function setDinnerEndTime(?string $dinnerEndTime): static
    {
        $this->dinnerEndTime = $dinnerEndTime;

        return $this;
    }

    public function getBarStartTime(): ?string
    {
        return $this->barStartTime;
    }

    public function setBarStartTime(?string $barStartTime): static
    {
        $this->barStartTime = $barStartTime;

        return $this;
    }

    public function getBarEndTime(): ?string
    {
        return $this->barEndTime;
    }

    public function setBarEndTime(?string $barEndTime): static
    {
        $this->barEndTime = $barEndTime;

        return $this;
    }

    public function getGeneralObservations(): ?string
    {
        return $this->generalObservations;
    }

    public function setGeneralObservations(?string $generalObservations): static
    {
        $this->generalObservations = $generalObservations;

        return $this;
    }

    public function isHasFullTimeReception(): ?bool
    {
        return $this->hasFullTimeReception;
    }

    public function setHasFullTimeReception(?bool $hasFullTimeReception): static
    {
        $this->hasFullTimeReception = $hasFullTimeReception;

        return $this;
    }

    public function isHasHeating(): ?bool
    {
        return $this->hasHeating;
    }

    public function setHasHeating(?bool $hasHeating): static
    {
        $this->hasHeating = $hasHeating;

        return $this;
    }

    public function isHasAirConditioner(): ?bool
    {
        return $this->hasAirConditioner;
    }

    public function setHasAirConditioner(?bool $hasAirConditioner): static
    {
        $this->hasAirConditioner = $hasAirConditioner;

        return $this;
    }

    public function isHasWiFi(): ?bool
    {
        return $this->hasWiFi;
    }

    public function setHasWiFi(?bool $hasWiFi): static
    {
        $this->hasWiFi = $hasWiFi;

        return $this;
    }

    public function isHasElevator(): ?bool
    {
        return $this->hasElevator;
    }

    public function setHasElevator(?bool $hasElevator): static
    {
        $this->hasElevator = $hasElevator;

        return $this;
    }

    public function isIsAdaptedReducedMobility(): ?bool
    {
        return $this->isAdaptedReducedMobility;
    }

    public function setIsAdaptedReducedMobility(?bool $isAdaptedReducedMobility): static
    {
        $this->isAdaptedReducedMobility = $isAdaptedReducedMobility;

        return $this;
    }

    public function isIsPetsAllowed(): ?bool
    {
        return $this->isPetsAllowed;
    }

    public function setIsPetsAllowed(?bool $isPetsAllowed): static
    {
        $this->isPetsAllowed = $isPetsAllowed;

        return $this;
    }

    public function isHasCradles(): ?bool
    {
        return $this->hasCradles;
    }

    public function setHasCradles(?bool $hasCradles): static
    {
        $this->hasCradles = $hasCradles;

        return $this;
    }

    public function isHasParking(): ?bool
    {
        return $this->hasParking;
    }

    public function setHasParking(?bool $hasParking): static
    {
        $this->hasParking = $hasParking;

        return $this;
    }

    public function isHasSpaAccess(): ?bool
    {
        return $this->hasSpaAccess;
    }

    public function setHasSpaAccess(?bool $hasSpaAccess): static
    {
        $this->hasSpaAccess = $hasSpaAccess;

        return $this;
    }

    public function isHasGym(): ?bool
    {
        return $this->hasGym;
    }

    public function setHasGym(?bool $hasGym): static
    {
        $this->hasGym = $hasGym;

        return $this;
    }

    public function isHasRestaurant(): ?bool
    {
        return $this->hasRestaurant;
    }

    public function setHasRestaurant(?bool $hasRestaurant): static
    {
        $this->hasRestaurant = $hasRestaurant;

        return $this;
    }

    public function isHasBar(): ?bool
    {
        return $this->hasBar;
    }

    public function setHasBar(?bool $hasBar): static
    {
        $this->hasBar = $hasBar;

        return $this;
    }

    public function isHasPoolBar(): ?bool
    {
        return $this->hasPoolBar;
    }

    public function setHasPoolBar(?bool $hasPoolBar): static
    {
        $this->hasPoolBar = $hasPoolBar;

        return $this;
    }

    public function isHasBuffetBreakfast(): ?bool
    {
        return $this->hasBuffetBreakfast;
    }

    public function setHasBuffetBreakfast(?bool $hasBuffetBreakfast): static
    {
        $this->hasBuffetBreakfast = $hasBuffetBreakfast;

        return $this;
    }

    public function isHasRoomService(): ?bool
    {
        return $this->hasRoomService;
    }

    public function setHasRoomService(?bool $hasRoomService): static
    {
        $this->hasRoomService = $hasRoomService;

        return $this;
    }

    public function isHasBuffetMeal(): ?bool
    {
        return $this->hasBuffetMeal;
    }

    public function setHasBuffetMeal(?bool $hasBuffetMeal): static
    {
        $this->hasBuffetMeal = $hasBuffetMeal;

        return $this;
    }

    public function isHasBuffetDinner(): ?bool
    {
        return $this->hasBuffetDinner;
    }

    public function setHasBuffetDinner(?bool $hasBuffetDinner): static
    {
        $this->hasBuffetDinner = $hasBuffetDinner;

        return $this;
    }

    public function isHasGlutenFreeFoods(): ?bool
    {
        return $this->hasGlutenFreeFoods;
    }

    public function setHasGlutenFreeFoods(?bool $hasGlutenFreeFoods): static
    {
        $this->hasGlutenFreeFoods = $hasGlutenFreeFoods;

        return $this;
    }

    public function isHasThemedRestaurants(): ?bool
    {
        return $this->hasThemedRestaurants;
    }

    public function setHasThemedRestaurants(?bool $hasThemedRestaurants): static
    {
        $this->hasThemedRestaurants = $hasThemedRestaurants;

        return $this;
    }

    public function isHasChillOut(): ?bool
    {
        return $this->hasChillOut;
    }

    public function setHasChillOut(?bool $hasChillOut): static
    {
        $this->hasChillOut = $hasChillOut;

        return $this;
    }

    public function isHasBeachClub(): ?bool
    {
        return $this->hasBeachClub;
    }

    public function setHasBeachClub(?bool $hasBeachClub): static
    {
        $this->hasBeachClub = $hasBeachClub;

        return $this;
    }

    public function isHasMassages(): ?bool
    {
        return $this->hasMassages;
    }

    public function setHasMassages(?bool $hasMassages): static
    {
        $this->hasMassages = $hasMassages;

        return $this;
    }

    public function isHasSauna(): ?bool
    {
        return $this->hasSauna;
    }

    public function setHasSauna(?bool $hasSauna): static
    {
        $this->hasSauna = $hasSauna;

        return $this;
    }

    public function isHasJacuzzi(): ?bool
    {
        return $this->hasJacuzzi;
    }

    public function setHasJacuzzi(?bool $hasJacuzzi): static
    {
        $this->hasJacuzzi = $hasJacuzzi;

        return $this;
    }

    public function isHasTurkishBath(): ?bool
    {
        return $this->hasTurkishBath;
    }

    public function setHasTurkishBath(?bool $hasTurkishBath): static
    {
        $this->hasTurkishBath = $hasTurkishBath;

        return $this;
    }

    public function isHasGameRoom(): ?bool
    {
        return $this->hasGameRoom;
    }

    public function setHasGameRoom(?bool $hasGameRoom): static
    {
        $this->hasGameRoom = $hasGameRoom;

        return $this;
    }

    public function isHasHairdresser(): ?bool
    {
        return $this->hasHairdresser;
    }

    public function setHasHairdresser(?bool $hasHairdresser): static
    {
        $this->hasHairdresser = $hasHairdresser;

        return $this;
    }

    public function isHasStoresAtHotel(): ?bool
    {
        return $this->hasStoresAtHotel;
    }

    public function setHasStoresAtHotel(?bool $hasStoresAtHotel): static
    {
        $this->hasStoresAtHotel = $hasStoresAtHotel;

        return $this;
    }

    public function isHasSunLoungers(): ?bool
    {
        return $this->hasSunLoungers;
    }

    public function setHasSunLoungers(?bool $hasSunLoungers): static
    {
        $this->hasSunLoungers = $hasSunLoungers;

        return $this;
    }

    public function isHasSuperMarket(): ?bool
    {
        return $this->hasSuperMarket;
    }

    public function setHasSuperMarket(?bool $hasSuperMarket): static
    {
        $this->hasSuperMarket = $hasSuperMarket;

        return $this;
    }

    public function isHasTerrace(): ?bool
    {
        return $this->hasTerrace;
    }

    public function setHasTerrace(?bool $hasTerrace): static
    {
        $this->hasTerrace = $hasTerrace;

        return $this;
    }

    public function isHasTennisCourt(): ?bool
    {
        return $this->hasTennisCourt;
    }

    public function setHasTennisCourt(?bool $hasTennisCourt): static
    {
        $this->hasTennisCourt = $hasTennisCourt;

        return $this;
    }

    public function isHasPaddleCourt(): ?bool
    {
        return $this->hasPaddleCourt;
    }

    public function setHasPaddleCourt(?bool $hasPaddleCourt): static
    {
        $this->hasPaddleCourt = $hasPaddleCourt;

        return $this;
    }

    public function isHasSoccerField(): ?bool
    {
        return $this->hasSoccerField;
    }

    public function setHasSoccerField(?bool $hasSoccerField): static
    {
        $this->hasSoccerField = $hasSoccerField;

        return $this;
    }

    public function isHasBasketballCourt(): ?bool
    {
        return $this->hasBasketballCourt;
    }

    public function setHasBasketballCourt(?bool $hasBasketballCourt): static
    {
        $this->hasBasketballCourt = $hasBasketballCourt;

        return $this;
    }

    public function isHasOutdoorSwimmingPool(): ?bool
    {
        return $this->hasOutdoorSwimmingPool;
    }

    public function setHasOutdoorSwimmingPool(?bool $hasOutdoorSwimmingPool): static
    {
        $this->hasOutdoorSwimmingPool = $hasOutdoorSwimmingPool;

        return $this;
    }

    public function isHasChildrenPool(): ?bool
    {
        return $this->hasChildrenPool;
    }

    public function setHasChildrenPool(?bool $hasChildrenPool): static
    {
        $this->hasChildrenPool = $hasChildrenPool;

        return $this;
    }

    public function isHasChildrenClub(): ?bool
    {
        return $this->hasChildrenClub;
    }

    public function setHasChildrenClub(?bool $hasChildrenClub): static
    {
        $this->hasChildrenClub = $hasChildrenClub;

        return $this;
    }

    public function isHasChildrenAnimation(): ?bool
    {
        return $this->hasChildrenAnimation;
    }

    public function setHasChildrenAnimation(?bool $hasChildrenAnimation): static
    {
        $this->hasChildrenAnimation = $hasChildrenAnimation;

        return $this;
    }

    public function isHasAdultAnimation(): ?bool
    {
        return $this->hasAdultAnimation;
    }

    public function setHasAdultAnimation(?bool $hasAdultAnimation): static
    {
        $this->hasAdultAnimation = $hasAdultAnimation;

        return $this;
    }

    public function isHasRoomTv(): ?bool
    {
        return $this->hasRoomTv;
    }

    public function setHasRoomTv(?bool $hasRoomTv): static
    {
        $this->hasRoomTv = $hasRoomTv;

        return $this;
    }

    public function isHasRoomPhone(): ?bool
    {
        return $this->hasRoomPhone;
    }

    public function setHasRoomPhone(?bool $hasRoomPhone): static
    {
        $this->hasRoomPhone = $hasRoomPhone;

        return $this;
    }

    public function isHasRoomTerrace(): ?bool
    {
        return $this->hasRoomTerrace;
    }

    public function setHasRoomTerrace(?bool $hasRoomTerrace): static
    {
        $this->hasRoomTerrace = $hasRoomTerrace;

        return $this;
    }

    public function isHasRoomHairDryer(): ?bool
    {
        return $this->hasRoomHairDryer;
    }

    public function setHasRoomHairDryer(?bool $hasRoomHairDryer): static
    {
        $this->hasRoomHairDryer = $hasRoomHairDryer;

        return $this;
    }

    public function isHasRoomHeating(): ?bool
    {
        return $this->hasRoomHeating;
    }

    public function setHasRoomHeating(?bool $hasRoomHeating): static
    {
        $this->hasRoomHeating = $hasRoomHeating;

        return $this;
    }

    public function isHasRoomAirConditioner(): ?bool
    {
        return $this->hasRoomAirConditioner;
    }

    public function setHasRoomAirConditioner(?bool $hasRoomAirConditioner): static
    {
        $this->hasRoomAirConditioner = $hasRoomAirConditioner;

        return $this;
    }

    public function isHasRoomSafeDepositBox(): ?bool
    {
        return $this->hasRoomSafeDepositBox;
    }

    public function setHasRoomSafeDepositBox(?bool $hasRoomSafeDepositBox): static
    {
        $this->hasRoomSafeDepositBox = $hasRoomSafeDepositBox;

        return $this;
    }

    public function isHasRoomDesk(): ?bool
    {
        return $this->hasRoomDesk;
    }

    public function setHasRoomDesk(?bool $hasRoomDesk): static
    {
        $this->hasRoomDesk = $hasRoomDesk;

        return $this;
    }

    public function isHasRoomWiFi(): ?bool
    {
        return $this->hasRoomWiFi;
    }

    public function setHasRoomWiFi(?bool $hasRoomWiFi): static
    {
        $this->hasRoomWiFi = $hasRoomWiFi;

        return $this;
    }

    public function isHasRoomWC(): ?bool
    {
        return $this->hasRoomWC;
    }

    public function setHasRoomWC(?bool $hasRoomWC): static
    {
        $this->hasRoomWC = $hasRoomWC;

        return $this;
    }

    public function isHasRoomShower(): ?bool
    {
        return $this->hasRoomShower;
    }

    public function setHasRoomShower(?bool $hasRoomShower): static
    {
        $this->hasRoomShower = $hasRoomShower;

        return $this;
    }

    public function isHasRoomBathtub(): ?bool
    {
        return $this->hasRoomBathtub;
    }

    public function setHasRoomBathtub(?bool $hasRoomBathtub): static
    {
        $this->hasRoomBathtub = $hasRoomBathtub;

        return $this;
    }

    public function isHasRoomBidet(): ?bool
    {
        return $this->hasRoomBidet;
    }

    public function setHasRoomBidet(?bool $hasRoomBidet): static
    {
        $this->hasRoomBidet = $hasRoomBidet;

        return $this;
    }

    public function getHotel(): ?Hotel
    {
        return $this->hotel;
    }

    public function setHotel(?Hotel $hotel): static
    {
        // unset the owning side of the relation if necessary
        if ($hotel === null && $this->hotel !== null) {
            $this->hotel->setServices(null);
        }

        // set the owning side of the relation if necessary
        if ($hotel !== null && $hotel->getServices() !== $this) {
            $hotel->setServices($this);
        }

        $this->hotel = $hotel;

        return $this;
    }
}
