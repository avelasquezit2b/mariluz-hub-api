<?php

namespace App\Entity;

use App\Repository\HeroModuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HeroModuleRepository::class)]
class HeroModule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'heroModule', targetEntity: HeroSlide::class)]
    private Collection $heroSlides;

    public function __construct()
    {
        $this->heroSlides = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, HeroSlide>
     */
    public function getHeroSlides(): Collection
    {
        return $this->heroSlides;
    }

    public function addHeroSlide(HeroSlide $heroSlide): static
    {
        if (!$this->heroSlides->contains($heroSlide)) {
            $this->heroSlides->add($heroSlide);
            $heroSlide->setHeroModule($this);
        }

        return $this;
    }

    public function removeHeroSlide(HeroSlide $heroSlide): static
    {
        if ($this->heroSlides->removeElement($heroSlide)) {
            // set the owning side to null (unless already changed)
            if ($heroSlide->getHeroModule() === $this) {
                $heroSlide->setHeroModule(null);
            }
        }

        return $this;
    }
}
