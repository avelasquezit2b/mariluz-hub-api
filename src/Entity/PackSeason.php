<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PackSeasonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PackSeasonRepository::class)]
#[ApiResource]
class PackSeason
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $ranges = null;

    #[ORM\ManyToOne(inversedBy: 'packSeasons')]
    private ?PackFee $packFee = null;

    #[ORM\OneToMany(mappedBy: 'packSeason', targetEntity: PackPrice::class)]
    private Collection $packPrices;

    public function __construct()
    {
        $this->packPrices = new ArrayCollection();
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

    public function getRanges(): ?array
    {
        return $this->ranges;
    }

    public function setRanges(?array $ranges): static
    {
        $this->ranges = $ranges;

        return $this;
    }

    public function getPackFee(): ?PackFee
    {
        return $this->packFee;
    }

    public function setPackFee(?PackFee $packFee): static
    {
        $this->packFee = $packFee;

        return $this;
    }

    /**
     * @return Collection<int, PackPrice>
     */
    public function getPackPrices(): Collection
    {
        return $this->packPrices;
    }

    public function addPackPrice(PackPrice $packPrice): static
    {
        if (!$this->packPrices->contains($packPrice)) {
            $this->packPrices->add($packPrice);
            $packPrice->setPackSeason($this);
        }

        return $this;
    }

    public function removePackPrice(PackPrice $packPrice): static
    {
        if ($this->packPrices->removeElement($packPrice)) {
            // set the owning side to null (unless already changed)
            if ($packPrice->getPackSeason() === $this) {
                $packPrice->setPackSeason(null);
            }
        }

        return $this;
    }
}
