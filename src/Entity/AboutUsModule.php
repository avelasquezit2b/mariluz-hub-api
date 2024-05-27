<?php

namespace App\Entity;

use App\Repository\AboutUsModuleRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AboutUsModuleRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["aboutUsReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "aboutUs"]],
        "put",
        "delete",
    ],
)]
class AboutUsModule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['aboutUsReduced', 'aboutUs', 'page'])]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['aboutUsReduced', 'aboutUs', 'page'])]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['aboutUsReduced', 'aboutUs', 'page'])]
    private ?string $firstIcon = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['aboutUsReduced', 'aboutUs', 'page'])]
    private ?string $firstSubtitle = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['aboutUsReduced', 'aboutUs', 'page'])]
    private ?string $firstText = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['aboutUsReduced', 'aboutUs', 'page'])]
    private ?string $secondIcon = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['aboutUsReduced', 'aboutUs', 'page'])]
    private ?string $secondSubtitle = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['aboutUsReduced', 'aboutUs', 'page'])]
    private ?string $secondText = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['aboutUsReduced', 'aboutUs', 'page'])]
    private ?string $thirdIcon = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['aboutUsReduced', 'aboutUs', 'page'])]
    private ?string $thirdSubtitle = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['aboutUsReduced', 'aboutUs', 'page'])]
    private ?string $thirdText = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['aboutUsReduced', 'aboutUs', 'page'])]
    private ?string $fourthIcon = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['aboutUsReduced', 'aboutUs', 'page'])]
    private ?string $fourthSubtitle = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['aboutUsReduced', 'aboutUs', 'page'])]
    private ?string $fourthText = null;

    #[ORM\OneToOne(inversedBy: 'aboutUsModule', cascade: ['persist', 'remove'])]
    #[Groups(['aboutUsReduced', 'aboutUs', 'page'])]
    private ?Module $module = null;

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

    public function getFirstIcon(): ?string
    {
        return $this->firstIcon;
    }

    public function setFirstIcon(?string $firstIcon): static
    {
        $this->firstIcon = $firstIcon;

        return $this;
    }

    public function getFirstSubtitle(): ?string
    {
        return $this->firstSubtitle;
    }

    public function setFirstSubtitle(?string $firstSubtitle): static
    {
        $this->firstSubtitle = $firstSubtitle;

        return $this;
    }

    public function getFirstText(): ?string
    {
        return $this->firstText;
    }

    public function setFirstText(?string $firstText): static
    {
        $this->firstText = $firstText;

        return $this;
    }

    public function getSecondIcon(): ?string
    {
        return $this->secondIcon;
    }

    public function setSecondIcon(?string $secondIcon): static
    {
        $this->secondIcon = $secondIcon;

        return $this;
    }

    public function getSecondSubtitle(): ?string
    {
        return $this->secondSubtitle;
    }

    public function setSecondSubtitle(?string $secondSubtitle): static
    {
        $this->secondSubtitle = $secondSubtitle;

        return $this;
    }

    public function getSecondText(): ?string
    {
        return $this->secondText;
    }

    public function setSecondText(?string $secondText): static
    {
        $this->secondText = $secondText;

        return $this;
    }

    public function getThirdIcon(): ?string
    {
        return $this->thirdIcon;
    }

    public function setThirdIcon(?string $thirdIcon): static
    {
        $this->thirdIcon = $thirdIcon;

        return $this;
    }

    public function getThirdSubtitle(): ?string
    {
        return $this->thirdSubtitle;
    }

    public function setThirdSubtitle(?string $thirdSubtitle): static
    {
        $this->thirdSubtitle = $thirdSubtitle;

        return $this;
    }

    public function getThirdText(): ?string
    {
        return $this->thirdText;
    }

    public function setThirdText(?string $thirdText): static
    {
        $this->thirdText = $thirdText;

        return $this;
    }

    public function getFourthIcon(): ?string
    {
        return $this->fourthIcon;
    }

    public function setFourthIcon(?string $fourthIcon): static
    {
        $this->fourthIcon = $fourthIcon;

        return $this;
    }

    public function getFourthSubtitle(): ?string
    {
        return $this->fourthSubtitle;
    }

    public function setFourthSubtitle(?string $fourthSubtitle): static
    {
        $this->fourthSubtitle = $fourthSubtitle;

        return $this;
    }

    public function getFourthText(): ?string
    {
        return $this->fourthText;
    }

    public function setFourthText(?string $fourthText): static
    {
        $this->fourthText = $fourthText;

        return $this;
    }

    public function getModule(): ?Module
    {
        return $this->module;
    }

    public function setModule(?Module $module): static
    {
        $this->module = $module;

        return $this;
    }
}
