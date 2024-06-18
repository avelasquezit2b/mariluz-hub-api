<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ThemeListModuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ThemeListModuleRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["themeListModuleReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "themeListModule"]],
        "put",
        "delete",
    ],
)]
class ThemeListModule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['themeListModuleReduced', 'themeListModule', 'page'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['themeListModuleReduced', 'themeListModule', 'page'])]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['themeListModuleReduced', 'themeListModule', 'page'])]
    private ?string $subtitle = null;

    #[ORM\ManyToMany(targetEntity: Theme::class, inversedBy: 'themeListModules')]
    #[Groups(['themeListModuleReduced', 'themeListModule', 'page'])]
    #[ORM\OrderBy(["position" => "ASC"])]
    private Collection $themes;

    #[ORM\OneToOne(mappedBy: 'themeListModule', cascade: ['persist', 'remove'])]
    #[Groups(['themeListModuleReduced', 'themeListModule', 'page'])]
    private ?Module $module = null;

    public function __construct()
    {
        $this->themes = new ArrayCollection();
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
        }

        return $this;
    }

    public function removeTheme(Theme $theme): static
    {
        $this->themes->removeElement($theme);

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
            $this->module->setThemeListModule(null);
        }

        // set the owning side of the relation if necessary
        if ($module !== null && $module->getThemeListModule() !== $this) {
            $module->setThemeListModule($this);
        }

        $this->module = $module;

        return $this;
    }
}
