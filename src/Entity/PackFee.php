<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PackFeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PackFeeRepository::class)]
#[ApiResource]
class PackFee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Modality $modality = null;

    #[ORM\OneToMany(mappedBy: 'packFee', targetEntity: PackSeason::class)]
    private Collection $packSeasons;

    #[ORM\ManyToOne(inversedBy: 'packFees')]
    private ?Pack $pack = null;

    public function __construct()
    {
        $this->packSeasons = new ArrayCollection();
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

    public function getModality(): ?Modality
    {
        return $this->modality;
    }

    public function setModality(?Modality $modality): static
    {
        $this->modality = $modality;

        return $this;
    }

    /**
     * @return Collection<int, PackSeason>
     */
    public function getPackSeasons(): Collection
    {
        return $this->packSeasons;
    }

    public function addPackSeason(PackSeason $packSeason): static
    {
        if (!$this->packSeasons->contains($packSeason)) {
            $this->packSeasons->add($packSeason);
            $packSeason->setPackFee($this);
        }

        return $this;
    }

    public function removePackSeason(PackSeason $packSeason): static
    {
        if ($this->packSeasons->removeElement($packSeason)) {
            // set the owning side to null (unless already changed)
            if ($packSeason->getPackFee() === $this) {
                $packSeason->setPackFee(null);
            }
        }

        return $this;
    }

    public function getPack(): ?Pack
    {
        return $this->pack;
    }

    public function setPack(?Pack $pack): static
    {
        $this->pack = $pack;

        return $this;
    }
}
