<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SeoRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeoRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["seoReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "seo"]],
        "put",
        "delete",
    ],
)]
class Seo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['seoReduced', 'seo', 'hotel', 'activity', 'extra'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['seoReduced', 'seo', 'hotel', 'activity', 'extra'])]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['seoReduced', 'seo', 'hotel', 'activity', 'extra'])]
    private ?string $description = null;

    #[ORM\OneToOne(mappedBy: 'seo', cascade: ['persist', 'remove'])]
    #[Groups(['seoReduced', 'seo'])]
    private ?Hotel $hotel = null;

    #[ORM\OneToOne(mappedBy: 'seo', cascade: ['persist', 'remove'])]
    #[Groups(['seoReduced', 'seo'])]
    private ?Activity $activity = null;

    #[ORM\OneToOne(mappedBy: 'seo', cascade: ['persist', 'remove'])]
    #[Groups(['seoReduced', 'seo'])]
    private ?Extra $extra = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    public function setActivity(?Activity $activity): static
    {
        // unset the owning side of the relation if necessary
        if ($activity === null && $this->activity !== null) {
            $this->activity->setSeo(null);
        }

        // set the owning side of the relation if necessary
        if ($activity !== null && $activity->getSeo() !== $this) {
            $activity->setSeo($this);
        }

        $this->activity = $activity;

        return $this;
    }

    public function getHotel(): ?Hotel
    {
        return $this->hotel;
    }

    public function setHotel(?Hotel $hotel): static
    {
        // unset the owning side of the relation if necessary
        if ($hotel === null && $this->hotel !== null) {
            $this->hotel->setSeo(null);
        }

        // set the owning side of the relation if necessary
        if ($hotel !== null && $hotel->getSeo() !== $this) {
            $hotel->setSeo($this);
        }

        $this->hotel = $hotel;

        return $this;
    }

    public function getExtra(): ?Extra
    {
        return $this->extra;
    }

    public function setExtra(?Extra $extra): static
    {
        // unset the owning side of the relation if necessary
        if ($extra === null && $this->extra !== null) {
            $this->extra->setSeo(null);
        }

        // set the owning side of the relation if necessary
        if ($extra !== null && $extra->getSeo() !== $this) {
            $extra->setSeo($this);
        }

        $this->extra = $extra;

        return $this;
    }
}
