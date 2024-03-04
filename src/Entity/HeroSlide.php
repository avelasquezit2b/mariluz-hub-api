<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\HeroSlideRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HeroSlideRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["heroSlideReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "heroSlide"]],
        "put",
        "delete",
    ],
)]
class HeroSlide
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['heroSlideReduced', 'heroSlide', 'page'])]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'heroSlide', targetEntity: MediaObject::class, cascade: ['remove'])]
    #[Groups(['heroSlideReduced', 'heroSlide', 'page'])]
    private Collection $media;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['heroSlideReduced', 'heroSlide', 'page'])]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['heroSlideReduced', 'heroSlide', 'page'])]
    private ?string $subtitle = null;

    #[ORM\ManyToOne(inversedBy: 'heroSlides')]
    #[Groups(['heroSlideReduced', 'heroSlide'])]
    private ?HeroModule $heroModule = null;

    public function __construct()
    {
        $this->media = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $medium->setHeroSlide($this);
        }

        return $this;
    }

    public function removeMedium(MediaObject $medium): static
    {
        if ($this->media->removeElement($medium)) {
            // set the owning side to null (unless already changed)
            if ($medium->getHeroSlide() === $this) {
                $medium->setHeroSlide(null);
            }
        }

        return $this;
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

    public function getHeroModule(): ?HeroModule
    {
        return $this->heroModule;
    }

    public function setHeroModule(?HeroModule $heroModule): static
    {
        $this->heroModule = $heroModule;

        return $this;
    }
}
