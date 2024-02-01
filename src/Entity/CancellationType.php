<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CancellationTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CancellationTypeRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["cancellationTypeReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "cancellationType"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class CancellationType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['cancellationTypeReduced', 'cancellationType', 'hotel'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['cancellationTypeReduced', 'cancellationType', 'hotel'])]
    private ?string $title = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['cancellationTypeReduced', 'cancellationType', 'hotel'])]
    private ?string $code = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['cancellationTypeReduced', 'cancellationType', 'hotel'])]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['cancellationTypeReduced', 'cancellationType', 'hotel'])]
    private ?string $customTitle = null;

    #[ORM\ManyToMany(targetEntity: HotelFee::class, mappedBy: 'cancellationTypes')]
    #[Groups(['cancellationTypeReduced', 'cancellationType'])]
    private Collection $hotelFees;

    #[ORM\OneToMany(mappedBy: 'cancellationType', targetEntity: HotelCondition::class)]
    #[Groups(['cancellationTypeReduced', 'cancellationType'])]
    private Collection $hotelConditions;

    public function __construct()
    {
        $this->hotelFees = new ArrayCollection();
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): static
    {
        $this->code = $code;

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

    public function getCustomTitle(): ?string
    {
        return $this->customTitle;
    }

    public function setCustomTitle(?string $customTitle): static
    {
        $this->customTitle = $customTitle;

        return $this;
    }

    /**
     * @return Collection<int, HotelFee>
     */
    public function getHotelFees(): Collection
    {
        return $this->hotelFees;
    }

    public function addHotelFee(HotelFee $hotelFee): static
    {
        if (!$this->hotelFees->contains($hotelFee)) {
            $this->hotelFees->add($hotelFee);
            $hotelFee->addCancellationType($this);
        }

        return $this;
    }

    public function removeHotelFee(HotelFee $hotelFee): static
    {
        if ($this->hotelFees->removeElement($hotelFee)) {
            $hotelFee->removeCancellationType($this);
        }

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
            $hotelCondition->setCancellationType($this);
        }

        return $this;
    }

    public function removeHotelCondition(HotelCondition $hotelCondition): static
    {
        if ($this->hotelConditions->removeElement($hotelCondition)) {
            // set the owning side to null (unless already changed)
            if ($hotelCondition->getCancellationType() === $this) {
                $hotelCondition->setCancellationType(null);
            }
        }

        return $this;
    }
}
