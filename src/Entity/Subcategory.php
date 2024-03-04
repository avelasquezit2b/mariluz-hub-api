<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\SubcategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SubcategoryRepository::class)]
#[ApiResource(
    paginationEnabled: false,
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["subcategoryReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "subcategory"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
#[ApiFilter(SearchFilter::class, properties: ['category.id' => 'exact'])]
class Subcategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['subcategoryReduced', 'subcategory', 'categoryReduced', 'category', 'activity'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['subcategoryReduced', 'subcategory', 'categoryReduced', 'category', 'activity'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['subcategory', 'category', 'activity'])]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'subCategories')]
    #[Groups(['subcategory'])]
    private ?Category $category = null;

    #[ORM\ManyToMany(targetEntity: Activity::class, mappedBy: 'subCategories')]
    #[Groups(['subcategory'])]
    private Collection $activities;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['subcategoryReduced', 'subcategory', 'categoryReduced', 'category', 'activity'])]
    private ?string $slug = null;

    #[ORM\ManyToMany(targetEntity: Pack::class, mappedBy: 'subCategories')]
    #[Groups(['subcategory'])]
    private Collection $packs;

    public function __construct()
    {
        $this->activities = new ArrayCollection();
        $this->packs = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

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
            $activity->addSubCategory($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): static
    {
        if ($this->activities->removeElement($activity)) {
            $activity->removeSubCategory($this);
        }

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
            $pack->addSubCategory($this);
        }

        return $this;
    }

    public function removePack(Pack $pack): static
    {
        if ($this->packs->removeElement($pack)) {
            $pack->removeSubCategory($this);
        }

        return $this;
    }
}
