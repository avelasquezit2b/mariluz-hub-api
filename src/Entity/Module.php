<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ModuleRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModuleRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["moduleReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "module"]],
        "put",
        "delete",
    ],
)]
class Module
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['moduleReduced', 'module', 'page'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['moduleReduced', 'module', 'page'])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'module')]
    #[Groups(['moduleReduced', 'module'])]
    private ?Section $section = null;

    #[ORM\OneToOne(inversedBy: 'module', cascade: ['persist', 'remove'])]
    #[Groups(['moduleReduced', 'module', 'page'])]
    private ?HeroModule $heroModule = null;

    #[ORM\OneToOne(inversedBy: 'module', cascade: ['persist', 'remove'])]
    #[Groups(['moduleReduced', 'module', 'page'])]
    private ?ProductListModule $productListModule = null;

    #[ORM\OneToOne(inversedBy: 'module', cascade: ['persist', 'remove'])]
    #[Groups(['moduleReduced', 'module', 'page'])]
    private ?SearchModule $searchModule = null;

    #[ORM\OneToOne(inversedBy: 'module', cascade: ['persist', 'remove'])]
    #[Groups(['moduleReduced', 'module', 'page'])]
    private ?ThemeListModule $themeListModule = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['moduleReduced', 'module', 'page'])]
    private ?string $type = null;

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

    public function getSection(): ?Section
    {
        return $this->section;
    }

    public function setSection(?Section $section): static
    {
        $this->section = $section;

        return $this;
    }

    public function getHeroModule(): ?HeroModule
    {
        return $this->heroModule;
    }

    public function setHeroModule(?HeroModule $heroModule): static
    {
        $this->heroModule = $heroModule;

        return $this;
    }

    public function getProductListModule(): ?ProductListModule
    {
        return $this->productListModule;
    }

    public function setProductListModule(?ProductListModule $productListModule): static
    {
        $this->productListModule = $productListModule;

        return $this;
    }

    public function getSearchModule(): ?SearchModule
    {
        return $this->searchModule;
    }

    public function setSearchModule(?SearchModule $searchModule): static
    {
        $this->searchModule = $searchModule;

        return $this;
    }

    public function getThemeListModule(): ?ThemeListModule
    {
        return $this->themeListModule;
    }

    public function setThemeListModule(?ThemeListModule $themeListModule): static
    {
        $this->themeListModule = $themeListModule;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }
}
