<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ThemeImageDescriptionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ThemeImageDescriptionRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "DESC"],
        "normalization_context" => ["groups" => ["themeImageDescriptionReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "themeImageDescription"]],
        "put",
        "delete",
    ],
)]
class ThemeImageDescription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['themeImageDescriptionReduced', 'themeImageDescription', 'page'])]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'themeImageDescription', cascade: ['persist', 'remove'])]
    #[Groups(['themeImageDescriptionReduced', 'themeImageDescription', 'page'])]
    private ?MediaObject $media = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['themeImageDescriptionReduced', 'themeImageDescription', 'page'])]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMedia(): ?MediaObject
    {
        return $this->media;
    }

    public function setMedia(?MediaObject $media): static
    {
        $this->media = $media;

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
}
