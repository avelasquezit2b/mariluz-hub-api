<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PageRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["pageReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "page"]],
        "put",
        "delete",
    ],
)]
class Page
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['pageReduced', 'page', 'web'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['pageReduced', 'page', 'web'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['pageReduced', 'page'])]
    private ?string $slug = null;

    #[ORM\Column]
    #[Groups(['pageReduced', 'page'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups(['pageReduced', 'page'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    #[Groups(['pageReduced', 'page'])]
    private ?bool $isActive = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Groups(['pageReduced', 'page'])]
    private ?Seo $seo = null;

    #[ORM\ManyToOne(inversedBy: 'pages')]
    #[Groups(['pageReduced', 'page'])]
    private ?Web $web = null;

    #[ORM\OneToMany(mappedBy: 'page', targetEntity: Section::class)]
    #[Groups(['pageReduced', 'page', 'web'])]
    #[ORM\OrderBy(["position" => "ASC"])]
    private Collection $sections;

    public function __construct()
    {
        $this->sections = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        //set automatically the created_at date
        if ($createdAt == null) {
            $this->createdAt = new \DateTime();
        } else {
            $this->createdAt = $createdAt;
        }

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

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

    public function getSeo(): ?Seo
    {
        return $this->seo;
    }

    public function setSeo(?Seo $seo): static
    {
        $this->seo = $seo;

        return $this;
    }

    public function getWeb(): ?Web
    {
        return $this->web;
    }

    public function setWeb(?Web $web): static
    {
        $this->web = $web;

        return $this;
    }

    /**
     * @return Collection<int, Section>
     */
    public function getSections(): Collection
    {
        return $this->sections;
    }

    public function addSection(Section $section): static
    {
        if (!$this->sections->contains($section)) {
            $this->sections->add($section);
            $section->setPage($this);
        }

        return $this;
    }

    public function removeSection(Section $section): static
    {
        if ($this->sections->removeElement($section)) {
            // set the owning side to null (unless already changed)
            if ($section->getPage() === $this) {
                $section->setPage(null);
            }
        }

        return $this;
    }
}
