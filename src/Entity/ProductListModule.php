<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProductListModuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductListModuleRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["productListReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "productList"]],
        "put",
        "delete",
    ],
)]
class ProductListModule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['productListReduced', 'productList', 'page'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['productListReduced', 'productList', 'page'])]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['productListReduced', 'productList', 'page'])]
    private ?string $subtitle = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['productListReduced', 'productList', 'page'])]
    private ?string $productType = null;

    #[ORM\ManyToMany(targetEntity: Hotel::class, inversedBy: 'productListModules')]
    #[Groups(['productListReduced', 'productList', 'page'])]
    private Collection $hotels;

    #[ORM\ManyToMany(targetEntity: Activity::class, inversedBy: 'productListModules')]
    #[Groups(['productListReduced', 'productList', 'page'])]
    private Collection $activities;

    #[ORM\ManyToOne(inversedBy: 'productListModules')]
    #[Groups(['productListReduced', 'productList', 'page'])]
    private ?Theme $theme = null;

    #[ORM\OneToOne(mappedBy: 'productListModule', cascade: ['persist', 'remove'])]
    #[Groups(['productListReduced', 'productList', 'page'])]
    private ?Module $module = null;

    #[ORM\ManyToMany(targetEntity: Pack::class, mappedBy: 'productListModules')]
    #[Groups(['productListReduced', 'productList', 'page'])]
    private Collection $packs;

    public function __construct()
    {
        $this->hotels = new ArrayCollection();
        $this->activities = new ArrayCollection();
        $this->packs = new ArrayCollection();
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

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(?string $subtitle): static
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function getProductType(): ?string
    {
        return $this->productType;
    }

    public function setProductType(?string $productType): static
    {
        $this->productType = $productType;

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
        }

        return $this;
    }

    public function removeHotel(Hotel $hotel): static
    {
        $this->hotels->removeElement($hotel);

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
        }

        return $this;
    }

    public function removeActivity(Activity $activity): static
    {
        $this->activities->removeElement($activity);

        return $this;
    }

    public function getTheme(): ?Theme
    {
        return $this->theme;
    }

    public function setTheme(?Theme $theme): static
    {
        $this->theme = $theme;

        return $this;
    }


    public function getModule(): ?Module
    {
        return $this->module;
    }

    public function setModule(?Module $module): static
    {
        // unset the owning side of the relation if necessary
        if ($module === null && $this->module !== null) {
            $this->module->setProductListModule(null);
        }

        // set the owning side of the relation if necessary
        if ($module !== null && $module->getProductListModule() !== $this) {
            $module->setProductListModule($this);
        }

        $this->module = $module;

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
            $pack->addProductListModule($this);
        }

        return $this;
    }

    public function removePack(Pack $pack): static
    {
        if ($this->packs->removeElement($pack)) {
            $pack->removeProductListModule($this);
        }

        return $this;
    }
}
