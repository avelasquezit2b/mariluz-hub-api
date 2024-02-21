<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SectionRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["sectionReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "section"]],
        "put",
        "delete",
    ],
)]
class Section
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['sectionReduced', 'section', 'page'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['sectionReduced', 'section', 'page'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['sectionReduced', 'section', 'page'])]
    private ?bool $isActive = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(['sectionReduced', 'section', 'page'])]
    private ?int $position = null;

    #[ORM\ManyToOne(inversedBy: 'sections')]
    #[Groups(['sectionReduced', 'section'])]
    private ?Page $page = null;

    #[ORM\OneToMany(mappedBy: 'section', targetEntity: Module::class, cascade: ['remove'])]
    #[Groups(['sectionReduced', 'section', 'page'])]
    private Collection $module;

    public function __construct()
    {
        $this->module = new ArrayCollection();
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

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function setPage(?Page $page): static
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return Collection<int, Module>
     */
    public function getModule(): Collection
    {
        return $this->module;
    }

    public function addModule(Module $module): static
    {
        if (!$this->module->contains($module)) {
            $this->module->add($module);
            $module->setSection($this);
        }

        return $this;
    }

    public function removeModule(Module $module): static
    {
        if ($this->module->removeElement($module)) {
            // set the owning side to null (unless already changed)
            if ($module->getSection() === $this) {
                $module->setSection(null);
            }
        }

        return $this;
    }
}
