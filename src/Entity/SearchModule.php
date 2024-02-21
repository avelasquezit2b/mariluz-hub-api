<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SearchModuleRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SearchModuleRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["searchReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "search"]],
        "put",
        "delete",
    ],
)]
class SearchModule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['searchReduced', 'search', 'page'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['searchReduced', 'search', 'page'])]
    private ?string $text = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['searchReduced', 'search', 'page'])]
    private ?string $buttonText = null;

    #[ORM\OneToOne(mappedBy: 'searchModule', cascade: ['persist', 'remove'])]
    #[Groups(['searchReduced', 'search', 'page'])]
    private ?Module $module = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getButtonText(): ?string
    {
        return $this->buttonText;
    }

    public function setButtonText(?string $buttonText): static
    {
        $this->buttonText = $buttonText;

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
            $this->module->setSearchModule(null);
        }

        // set the owning side of the relation if necessary
        if ($module !== null && $module->getSearchModule() !== $this) {
            $module->setSearchModule($this);
        }

        $this->module = $module;

        return $this;
    }
}
