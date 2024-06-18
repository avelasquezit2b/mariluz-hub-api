<?php

namespace App\Entity;

use App\Repository\MenuItemRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;

#[ORM\Entity(repositoryClass: MenuItemRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "DESC"],
        "normalization_context" => ["groups" => ["menuItemReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "menuItem"]],
        "put",
        "delete"
    ],
)]
class MenuItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['menuItemReduced', 'menuItem', 'menuModule'])]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['menuItemReduced', 'menuItem', 'menuModule'])]
    private ?string $type = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['menuItemReduced', 'menuItem', 'menuModule'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    #[Groups(['menuItemReduced', 'menuItem', 'menuModule'])]
    private ?array $links = null;

    #[ORM\ManyToOne(inversedBy: 'menuItems')]
    #[Groups(['menuItemReduced', 'menuItem', 'menuModule'])]
    private ?MenuModule $menuModule = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    #[Groups(['menuItemReduced', 'menuItem', 'menuModule'])]
    private ?int $position = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getLinks(): ?array
    {
        return $this->links;
    }

    public function setLinks(?array $links): static
    {
        $this->links = $links;

        return $this;
    }

    public function getMenuModule(): ?MenuModule
    {
        return $this->menuModule;
    }

    public function setMenuModule(?MenuModule $menuModule): static
    {
        $this->menuModule = $menuModule;

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
