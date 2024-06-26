<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\ThemeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ThemeRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "DESC"],
        "normalization_context" => ["groups" => ["themeReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "theme"]],
        "put",
        "delete",
    ],
)]
#[ApiFilter(SearchFilter::class, properties: ['slug' => 'exact'])]
class Theme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['themeReduced', 'theme', 'page'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['themeReduced', 'theme', 'page'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['themeReduced', 'theme', 'page'])]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'theme', targetEntity: ProductListModule::class)]
    #[Groups(['themeReduced', 'theme'])]
    private Collection $productListModules;

    #[ORM\ManyToMany(targetEntity: ThemeListModule::class, mappedBy: 'themes')]
    #[Groups(['themeReduced', 'theme'])]
    private Collection $themeListModules;

    #[ORM\OneToMany(mappedBy: 'theme', targetEntity: MediaObject::class)]
    #[Groups(['themeReduced', 'theme', 'page'])]
    #[ORM\OrderBy(["position" => "ASC"])]
    private Collection $media;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['themeReduced', 'theme', 'page'])]
    private ?string $slug = null;

    #[ORM\ManyToMany(targetEntity: Activity::class, inversedBy: 'themes')]
    #[Groups(['themeReduced', 'theme', 'page'])]
    private Collection $activities;

    #[ORM\ManyToMany(targetEntity: Hotel::class, inversedBy: 'themes')]
    #[Groups(['themeReduced', 'theme', 'page'])]
    private Collection $hotels;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['themeReduced', 'theme', 'page'])]
    private ?string $subtitle = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['themeReduced', 'theme', 'page'])]
    private ?string $firstTextBlock = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['themeReduced', 'theme', 'page'])]
    private ?string $secondTextBlock = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['themeReduced', 'theme', 'page'])]
    private ?string $secondTextBlockTitle = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['themeReduced', 'theme', 'page'])]
    private ?string $thirdTextBlock = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    #[Groups(['themeReduced', 'theme', 'page'])]
    private ?int $position = null;

    public function __construct()
    {
        $this->productListModules = new ArrayCollection();
        $this->themeListModules = new ArrayCollection();
        $this->media = new ArrayCollection();
        $this->activities = new ArrayCollection();
        $this->hotels = new ArrayCollection();
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
            $productListModule->setTheme($this);
        }

        return $this;
    }

    public function removeProductListModule(ProductListModule $productListModule): static
    {
        if ($this->productListModules->removeElement($productListModule)) {
            // set the owning side to null (unless already changed)
            if ($productListModule->getTheme() === $this) {
                $productListModule->setTheme(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ThemeListModule>
     */
    public function getThemeListModules(): Collection
    {
        return $this->themeListModules;
    }

    public function addThemeListModule(ThemeListModule $themeListModule): static
    {
        if (!$this->themeListModules->contains($themeListModule)) {
            $this->themeListModules->add($themeListModule);
            $themeListModule->addTheme($this);
        }

        return $this;
    }

    public function removeThemeListModule(ThemeListModule $themeListModule): static
    {
        if ($this->themeListModules->removeElement($themeListModule)) {
            $themeListModule->removeTheme($this);
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
            $medium->setTheme($this);
        }

        return $this;
    }

    public function removeMedium(MediaObject $medium): static
    {
        if ($this->media->removeElement($medium)) {
            // set the owning side to null (unless already changed)
            if ($medium->getTheme() === $this) {
                $medium->setTheme(null);
            }
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

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(?string $subtitle): static
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function getFirstTextBlock(): ?string
    {
        return $this->firstTextBlock;
    }

    public function setFirstTextBlock(?string $firstTextBlock): static
    {
        $this->firstTextBlock = $firstTextBlock;

        return $this;
    }

    public function getSecondTextBlock(): ?string
    {
        return $this->secondTextBlock;
    }

    public function setSecondTextBlock(?string $secondTextBlock): static
    {
        $this->secondTextBlock = $secondTextBlock;

        return $this;
    }

    public function getSecondTextBlockTitle(): ?string
    {
        return $this->secondTextBlockTitle;
    }

    public function setSecondTextBlockTitle(?string $secondTextBlockTitle): static
    {
        $this->secondTextBlockTitle = $secondTextBlockTitle;

        return $this;
    }

    public function getThirdTextBlock(): ?string
    {
        return $this->thirdTextBlock;
    }

    public function setThirdTextBlock(?string $thirdTextBlock): static
    {
        $this->thirdTextBlock = $thirdTextBlock;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): static
    {
        $this->position = $position;

        return $this;
    }
}
