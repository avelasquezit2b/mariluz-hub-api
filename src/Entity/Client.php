<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["clientReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "client"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['clientReduced', 'client'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['clientReduced', 'client'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['clientReduced','client'])]
    private ?string $email = null;

    #[ORM\Column(length:255, nullable: true)]
    #[Groups(['clientReduced','client'])]
    private ?string $phone = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['client'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: HotelBooking::class)]
    #[Groups(['clientReduced', 'client'])]
    private Collection $bookings;

    #[ORM\ManyToMany(targetEntity: CommunicationTemplate::class, mappedBy: 'client')]
    #[Groups(['clientReduced', 'client'])]
    private Collection $communicationTemplates;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: ActivityBooking::class)]
    private Collection $activityBookings;

    public function __construct()
    {
        $this->bookings = new ArrayCollection();
        $this->communicationTemplates = new ArrayCollection();
        $this->activityBookings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(?int $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        //set automatically the created_at date
        if ($createdAt == null) {
            $this->createdAt = new \DateTime();
        } else {
            $this->createdAt = $createdAt;
        }

        return $this;
    }

    /**
     * @return Collection<int, HotelBooking>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(HotelBooking $booking): static
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings->add($booking);
            $booking->setClient($this);
        }

        return $this;
    }

    public function removeBooking(HotelBooking $booking): static
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getClient() === $this) {
                $booking->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommunicationTemplate>
     */
    public function getCommunicationTemplates(): Collection
    {
        return $this->communicationTemplates;
    }

    public function addCommunicationTemplate(CommunicationTemplate $communicationTemplate): static
    {
        if (!$this->communicationTemplates->contains($communicationTemplate)) {
            $this->communicationTemplates->add($communicationTemplate);
            $communicationTemplate->addClient($this);
        }

        return $this;
    }

    public function removeCommunicationTemplate(CommunicationTemplate $communicationTemplate): static
    {
        if ($this->communicationTemplates->removeElement($communicationTemplate)) {
            $communicationTemplate->removeClient($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, ActivityBooking>
     */
    public function getActivityBookings(): Collection
    {
        return $this->activityBookings;
    }

    public function addActivityBooking(ActivityBooking $activityBooking): static
    {
        if (!$this->activityBookings->contains($activityBooking)) {
            $this->activityBookings->add($activityBooking);
            $activityBooking->setClient($this);
        }

        return $this;
    }

    public function removeActivityBooking(ActivityBooking $activityBooking): static
    {
        if ($this->activityBookings->removeElement($activityBooking)) {
            // set the owning side to null (unless already changed)
            if ($activityBooking->getClient() === $this) {
                $activityBooking->setClient(null);
            }
        }

        return $this;
    }
}
