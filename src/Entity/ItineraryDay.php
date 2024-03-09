<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ItineraryDayRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItineraryDayRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["itineraryDayReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "itineraryDay"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class ItineraryDay
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['itineraryDayReduced', 'itineraryDay', 'modality'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(['itineraryDayReduced', 'itineraryDay', 'modality'])]
    private ?int $number = null;

    #[ORM\ManyToMany(targetEntity: Hotel::class, inversedBy: 'itineraryDays')]
    #[Groups(['itineraryDayReduced', 'itineraryDay', 'modality'])]
    private Collection $hotels;

    #[ORM\ManyToMany(targetEntity: Activity::class, inversedBy: 'itineraryDays')]
    #[Groups(['itineraryDayReduced', 'itineraryDay', 'modality'])]
    private Collection $activities;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['itineraryDayReduced', 'itineraryDay', 'modality'])]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'itineraryDays')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['itineraryDayReduced', 'itineraryDay', 'modality'])]
    private ?Modality $modality = null;

    public function __construct()
    {
        $this->hotels = new ArrayCollection();
        $this->activities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return Collection<int, Hotel>
     */
    public function getHotels(): Collection
    {
        return $this->hotels;
    }

    public function addHotel(Hotel $hotel): static
    {
        if (!$this->hotels->contains($hotel)) {
            $this->hotels->add($hotel);
        }

        return $this;
    }

    public function removeHotel(Hotel $hotel): static
    {
        $this->hotels->removeElement($hotel);

        return $this;
    }

    /**
     * @return Collection<int, Activity>
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(Activity $activity): static
    {
        if (!$this->activities->contains($activity)) {
            $this->activities->add($activity);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): static
    {
        $this->activities->removeElement($activity);

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

    public function getModality(): ?Modality
    {
        return $this->modality;
    }

    public function setModality(?Modality $modality): static
    {
        $this->modality = $modality;

        return $this;
    }
}
