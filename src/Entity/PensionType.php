<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PensionTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PensionTypeRepository::class)]
#[ApiResource]
class PensionType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $code = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: HotelServices::class, mappedBy: 'pensionTypes')]
    private Collection $hotelServices;

    public function __construct()
    {
        $this->hotelServices = new ArrayCollection();
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

    /**
     * @return Collection<int, HotelServices>
     */
    public function getHotelServices(): Collection
    {
        return $this->hotelServices;
    }

    public function addHotelService(HotelServices $hotelService): static
    {
        if (!$this->hotelServices->contains($hotelService)) {
            $this->hotelServices->add($hotelService);
            $hotelService->addPensionType($this);
        }

        return $this;
    }

    public function removeHotelService(HotelServices $hotelService): static
    {
        if ($this->hotelServices->removeElement($hotelService)) {
            $hotelService->removePensionType($this);
        }

        return $this;
    }
}
