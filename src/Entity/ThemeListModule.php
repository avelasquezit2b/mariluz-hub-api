<?php

namespace App\Entity;

use App\Repository\ThemeListModuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ThemeListModuleRepository::class)]
class ThemeListModule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $subtitle = null;

    #[ORM\ManyToMany(targetEntity: Theme::class, inversedBy: 'themeListModules')]
    private Collection $themes;

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
}
