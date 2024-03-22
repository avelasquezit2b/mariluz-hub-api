<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\PackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PackRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["packReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "pack"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'partial', 'title' => 'partial'])]
class Pack
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['packReduced', 'pack'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['packReduced', 'pack', 'page', 'productList'])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Groups(['packReduced', 'pack', 'page', 'productList'])]
    private ?string $slug = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['packReduced', 'pack', 'page', 'productList'])]
    private ?string $shortDescription = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['pack'])]
    private ?string $extendedDescription = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['pack'])]
    private ?string $highlights = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['pack'])]
    private ?string $includes = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['pack'])]
    private ?string $notIncludes = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['pack'])]
    private ?string $carry = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['pack'])]
    private ?string $importantInformation = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['pack'])]
    private ?string $cancelationConditions = null;

    #[ORM\Column]
    #[Groups(['pack'])]
    private ?bool $isActive = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'packs')]
    #[Groups(['packReduced', 'pack'])]
    private Collection $categories;

    #[ORM\ManyToMany(targetEntity: Subcategory::class, inversedBy: 'packs')]
    #[Groups(['packReduced', 'pack'])]
    private Collection $subCategories;

    #[ORM\OneToMany(mappedBy: 'pack', targetEntity: Modality::class, cascade: ['remove'])]
    #[Groups(['pack'])]
    private Collection $modalities;

    #[ORM\OneToMany(mappedBy: 'pack', targetEntity: MediaObject::class, cascade: ['remove'])]
    #[Groups(['packReduced', 'pack', 'page', 'productList'])]
    #[ORM\OrderBy(["position" => "ASC"])]
    #[ApiSubresource]
    private Collection $media;

    #[ORM\ManyToOne(inversedBy: 'packs')]
    #[Groups(['packReduced', 'pack', 'page', 'productList'])]
    private ?Location $location = null;

    #[ORM\ManyToMany(targetEntity: Zone::class, inversedBy: 'packs')]
    #[Groups(['packReduced', 'pack', 'page', 'productList'])]
    private Collection $zones;

    #[ORM\ManyToMany(targetEntity: ProductListModule::class, inversedBy: 'packs')]
    private Collection $productListModules;

    #[ORM\OneToMany(mappedBy: 'pack', targetEntity: PackFee::class)]
    private Collection $packFees;

    #[Groups(['packReduced', 'pack', 'page', 'productList'])]
    private $price;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->subCategories = new ArrayCollection();
        $this->modalities = new ArrayCollection();
        $this->media = new ArrayCollection();
        $this->zones = new ArrayCollection();
        $this->productListModules = new ArrayCollection();
        $this->packFees = new ArrayCollection();
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
            $modality->setPack($this);
        }

        return $this;
    }

    public function removeModality(Modality $modality): static
    {
        if ($this->modalities->removeElement($modality)) {
            // set the owning side to null (unless already changed)
            if ($modality->getPack() === $this) {
                $modality->setPack(null);
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
            $medium->setPack($this);
        }

        return $this;
    }

    public function removeMedium(MediaObject $medium): static
    {
        if ($this->media->removeElement($medium)) {
            // set the owning side to null (unless already changed)
            if ($medium->getPack() === $this) {
                $medium->setPack(null);
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
        }

        return $this;
    }

    public function removeProductListModule(ProductListModule $productListModule): static
    {
        $this->productListModules->removeElement($productListModule);

        return $this;
    }

    /**
     * @return Collection<int, PackFee>
     */
    public function getPackFees(): Collection
    {
        return $this->packFees;
    }

    public function addPackFee(PackFee $packFee): static
    {
        if (!$this->packFees->contains($packFee)) {
            $this->packFees->add($packFee);
            $packFee->setPack($this);
        }

        return $this;
    }

    public function removePackFee(PackFee $packFee): static
    {
        if ($this->packFees->removeElement($packFee)) {
            // set the owning side to null (unless already changed)
            if ($packFee->getPack() === $this) {
                $packFee->setPack(null);
            }
        }

        return $this;
    }

    public function getPrice(): ?string
    {
        $price = null;
        foreach($this->getModalities() as $modality) {
            if (!$price) {
                $price = $modality->getPrice();
            } else if ($price > $modality->getPrice()) {
                $price = $modality->getPrice();
            }
        }
        return $price;
    }
}
