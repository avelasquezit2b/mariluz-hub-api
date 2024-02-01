<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\HotelSeasonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: HotelSeasonRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["hotelSeasonReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "hotelSeason"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class HotelSeason
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['hotelSeasonReduced', 'hotelSeason', 'hotel'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['hotelSeasonReduced', 'hotelSeason', 'hotel'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    #[Groups(['hotelSeasonReduced', 'hotelSeason', 'hotel'])]
    private ?array $ranges = null;

    #[ORM\ManyToOne(inversedBy: 'hotelSeasons')]
    #[Groups(['hotelSeasonReduced', 'hotelSeason'])]
    private ?HotelFee $hotelFee = null;

    #[ORM\OneToMany(mappedBy: 'hotelSeason', targetEntity: HotelCondition::class, cascade: ['remove'])]
    #[Groups(['hotelSeasonReduced', 'hotelSeason', 'hotel'])]
    private Collection $hotelConditions;

    public function __construct()
    {
        $this->hotelConditions = new ArrayCollection();
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

    public function getHotelFee(): ?HotelFee
    {
        return $this->hotelFee;
    }

    public function setHotelFee(?HotelFee $hotelFee): static
    {
        $this->hotelFee = $hotelFee;

        return $this;
    }

    /**
     * @return Collection<int, HotelCondition>
     */
    public function getHotelConditions(): Collection
    {
        return $this->hotelConditions;
    }

    public function addHotelCondition(HotelCondition $hotelCondition): static
    {
        if (!$this->hotelConditions->contains($hotelCondition)) {
            $this->hotelConditions->add($hotelCondition);
            $hotelCondition->setHotelSeason($this);
        }

        return $this;
    }

    public function removeHotelCondition(HotelCondition $hotelCondition): static
    {
        if ($this->hotelConditions->removeElement($hotelCondition)) {
            // set the owning side to null (unless already changed)
            if ($hotelCondition->getHotelSeason() === $this) {
                $hotelCondition->setHotelSeason(null);
            }
        }

        return $this;
    }
}
