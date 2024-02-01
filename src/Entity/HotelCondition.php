<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\HotelConditionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: HotelConditionRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["hotelConditionReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "hotelCondition"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class HotelCondition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['hotelConditionReduced', 'hotelCondition', 'hotel'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'hotelConditions')]
    #[Groups(['hotelConditionReduced', 'hotelCondition', 'hotel'])]
    private ?CancellationType $cancellationType = null;

    #[ORM\OneToMany(mappedBy: 'hotelCondition', targetEntity: RoomCondition::class, cascade: ['remove'])]
    #[Groups(['hotelConditionReduced', 'hotelCondition', 'hotel'])]
    private Collection $roomConditions;

    #[ORM\ManyToOne(inversedBy: 'hotelConditions')]
    #[Groups(['hotelConditionReduced', 'hotelCondition'])]
    private ?HotelSeason $hotelSeason = null;

    public function __construct()
    {
        $this->roomConditions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCancellationType(): ?CancellationType
    {
        return $this->cancellationType;
    }

    public function setCancellationType(?CancellationType $cancellationType): static
    {
        $this->cancellationType = $cancellationType;

        return $this;
    }

    /**
     * @return Collection<int, RoomCondition>
     */
    public function getRoomConditions(): Collection
    {
        return $this->roomConditions;
    }

    public function addRoomCondition(RoomCondition $roomCondition): static
    {
        if (!$this->roomConditions->contains($roomCondition)) {
            $this->roomConditions->add($roomCondition);
            $roomCondition->setHotelCondition($this);
        }

        return $this;
    }

    public function removeRoomCondition(RoomCondition $roomCondition): static
    {
        if ($this->roomConditions->removeElement($roomCondition)) {
            // set the owning side to null (unless already changed)
            if ($roomCondition->getHotelCondition() === $this) {
                $roomCondition->setHotelCondition(null);
            }
        }

        return $this;
    }

    public function getHotelSeason(): ?HotelSeason
    {
        return $this->hotelSeason;
    }

    public function setHotelSeason(?HotelSeason $hotelSeason): static
    {
        $this->hotelSeason = $hotelSeason;

        return $this;
    }
}
