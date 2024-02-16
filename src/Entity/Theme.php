<?php

namespace App\Entity;

use App\Repository\ThemeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ThemeRepository::class)]
class Theme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'theme', targetEntity: ProductListModule::class)]
    private Collection $productListModules;

    #[ORM\ManyToMany(targetEntity: ThemeListModule::class, mappedBy: 'themes')]
    private Collection $themeListModules;

    #[ORM\OneToMany(mappedBy: 'theme', targetEntity: MediaObject::class)]
    private Collection $media;

    public function __construct()
    {
        $this->productListModules = new ArrayCollection();
        $this->themeListModules = new ArrayCollection();
        $this->media = new ArrayCollection();
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
}
