<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "DESC"],
        "normalization_context" => ["groups" => ["locationReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "location"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['locationReduced', 'location', 'activityReduced', 'activity', 'hotelReduced', 'hotel', 'packReduced', 'pack', 'extra'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['locationReduced', 'location', 'activityReduced', 'activity', 'hotelReduced', 'hotel', 'packReduced', 'pack', 'extra', 'page', 'productList', 'booking'])]
    private ?string $name = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['location', 'activity', 'hotel', 'pack', 'extra'])]
    private ?string $latitude = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['location', 'activity', 'hotel', 'pack', 'extra'])]
    private ?string $longitude = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['location', 'activity', 'hotel', 'pack', 'extra'])]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'location', targetEntity: Zone::class, cascade: ['persist', 'remove'])]
    #[Groups(['locationReduced', 'location'])]
    #[ApiSubresource]
    private Collection $zones;

    #[ORM\OneToMany(mappedBy: 'location', targetEntity: Activity::class)]
    private Collection $activities;

    #[ORM\OneToMany(mappedBy: 'location', targetEntity: Hotel::class)]
    private Collection $hotels;

    #[ORM\OneToMany(mappedBy: 'location', targetEntity: Pack::class)]
    private Collection $packs;

    #[ORM\OneToMany(mappedBy: 'location', targetEntity: Extra::class)]
    private Collection $extras;

    public function __construct()
    {
        $this->zones = new ArrayCollection();
        $this->activities = new ArrayCollection();
        $this->hotels = new ArrayCollection();
        $this->packs = new ArrayCollection();
        $this->extras = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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
            $zone->setLocation($this);
        }

        return $this;
    }

    public function removeZone(Zone $zone): static
    {
        if ($this->zones->removeElement($zone)) {
            // set the owning side to null (unless already changed)
            if ($zone->getLocation() === $this) {
                $zone->setLocation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Activity>
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(Activity $activity): static
    {
        if (!$this->activities->contains($activity)) {
            $this->activities->add($activity);
            $activity->setLocation($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): static
    {
        if ($this->activities->removeElement($activity)) {
            // set the owning side to null (unless already changed)
            if ($activity->getLocation() === $this) {
                $activity->setLocation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Hotel>
     */
    public function getHotels(): Collection
    {
        return $this->hotels;
    }

    public function addHotel(Hotel $hotel): static
    {
        if (!$this->hotels->contains($hotel)) {
            $this->hotels->add($hotel);
            $hotel->setLocation($this);
        }

        return $this;
    }

    public function removeHotel(Hotel $hotel): static
    {
        if ($this->hotels->removeElement($hotel)) {
            // set the owning side to null (unless already changed)
            if ($hotel->getLocation() === $this) {
                $hotel->setLocation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Pack>
     */
    public function getPacks(): Collection
    {
        return $this->packs;
    }

    public function addPack(Pack $pack): static
    {
        if (!$this->packs->contains($pack)) {
            $this->packs->add($pack);
            $pack->setLocation($this);
        }

        return $this;
    }

    public function removePack(Pack $pack): static
    {
        if ($this->packs->removeElement($pack)) {
            // set the owning side to null (unless already changed)
            if ($pack->getLocation() === $this) {
                $pack->setLocation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Extra>
     */
    public function getExtras(): Collection
    {
        return $this->extras;
    }

    public function addExtra(Extra $extra): static
    {
        if (!$this->extras->contains($extra)) {
            $this->extras->add($extra);
            $extra->setLocation($this);
        }

        return $this;
    }

    public function removeExtra(Extra $extra): static
    {
        if ($this->extras->removeElement($extra)) {
            // set the owning side to null (unless already changed)
            if ($extra->getLocation() === $this) {
                $extra->setLocation(null);
            }
        }

        return $this;
    }
}
