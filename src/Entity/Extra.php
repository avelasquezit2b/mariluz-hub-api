<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ExtraRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExtraRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["extraReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "extra"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class Extra
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['extraReduced', 'extra', 'supplierReduced', 'supplier'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['extraReduced', 'extra', 'supplierReduced', 'supplier', 'pack'])]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['extraReduced', 'extra'])]
    private ?string $slug = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['extraReduced', 'extra'])]
    private ?string $shortDescription = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['extra'])]
    private ?string $extendedDescription = null;

    #[ORM\ManyToOne(inversedBy: 'extras')]
    #[Groups(['extra', 'supplier'])]
    private ?Supplier $supplier = null;

    #[ORM\Column]
    #[Groups(['extra'])]
    private ?bool $isActive = null;

    #[ORM\OneToMany(mappedBy: 'extra', targetEntity: MediaObject::class)]
    #[Groups(['extraReduced', 'extra'])]
    private Collection $media;

    #[ORM\ManyToOne(inversedBy: 'extras')]
    #[Groups(['extraReduced', 'extra'])]
    private ?Location $location = null;

    #[ORM\ManyToMany(targetEntity: Zone::class, inversedBy: 'extras')]
    #[Groups(['extraReduced', 'extra'])]
    private Collection $zones;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['extraReduced', 'extra'])]
    private ?string $price = null;

    #[ORM\ManyToMany(targetEntity: Modality::class, mappedBy: 'packExtras')]
    private Collection $packModalities;

    #[ORM\OneToMany(mappedBy: 'extra', targetEntity: PackPrice::class)]
    private Collection $packPrices;

    public function __construct()
    {
        $this->media = new ArrayCollection();
        $this->zones = new ArrayCollection();
        $this->packModalities = new ArrayCollection();
        $this->packPrices = new ArrayCollection();
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

    public function setSlug(?string $slug): static
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
            $medium->setExtra($this);
        }

        return $this;
    }

    public function removeMedium(MediaObject $medium): static
    {
        if ($this->media->removeElement($medium)) {
            // set the owning side to null (unless already changed)
            if ($medium->getExtra() === $this) {
                $medium->setExtra(null);
            }
        }

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

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): static
    {
        $this->price = $price;

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
            $packModality->addPackExtra($this);
        }

        return $this;
    }

    public function removePackModality(Modality $packModality): static
    {
        if ($this->packModalities->removeElement($packModality)) {
            $packModality->removePackExtra($this);
        }

        return $this;
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
            $packPrice->setExtra($this);
        }

        return $this;
    }

    public function removePackPrice(PackPrice $packPrice): static
    {
        if ($this->packPrices->removeElement($packPrice)) {
            // set the owning side to null (unless already changed)
            if ($packPrice->getExtra() === $this) {
                $packPrice->setExtra(null);
            }
        }

        return $this;
    }
}
