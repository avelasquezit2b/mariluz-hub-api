<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SupplierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SupplierRepository::class)]
#[ApiResource(
    paginationEnabled: false,
    attributes: [
        "order" => ["name" => "ASC"],
        "normalization_context" => ["groups" => ["supplierReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "supplier"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class Supplier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['supplierReduced', 'supplier'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['supplierReduced', 'supplier', 'booking'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['supplierReduced', 'supplier'])]
    private ?bool $isActive = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['supplierReduced', 'supplier'])]
    private ?string $contactPerson = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['supplierReduced', 'supplier'])]
    private ?string $contactEmail = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['supplierReduced', 'supplier'])]
    private ?string $contactPhone = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['supplierReduced', 'supplier', 'booking'])]
    private ?string $bookingEmail = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['supplierReduced', 'supplier', 'booking'])]
    private ?string $bookingPhone = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['supplierReduced', 'supplier'])]
    private ?bool $hasWhatsapp = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['supplierReduced', 'supplier'])]
    private ?string $internalNotes = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['supplierReduced', 'supplier'])]
    private ?string $legalName = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['supplierReduced', 'supplier'])]
    private ?string $documentType = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['supplierReduced', 'supplier'])]
    private ?string $document = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['supplierReduced', 'supplier'])]
    private ?string $adminEmail = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['supplierReduced', 'supplier'])]
    private ?string $adminPhone = null;

    #[ORM\OneToMany(mappedBy: 'supplier', targetEntity: Activity::class)]
    #[Groups(['supplierReduced', 'supplier'])]
    private Collection $activities;

    #[ORM\OneToMany(mappedBy: 'supplier', targetEntity: Hotel::class)]
    #[Groups(['supplierReduced', 'supplier'])]
    private Collection $hotels;

    #[ORM\OneToMany(mappedBy: 'supplier', targetEntity: Extra::class)]
    #[Groups(['supplierReduced', 'supplier'])]
    private Collection $extras;

    #[ORM\ManyToMany(mappedBy: 'supplier', targetEntity: CommunicationTemplate::class)]
    #[Groups(['supplierReduced', 'supplier'])]
    private Collection $communicationTemplates;

    public function __construct()
    {
        $this->activities = new ArrayCollection();
        $this->hotels = new ArrayCollection();
        $this->extras = new ArrayCollection();
        $this->communicationTemplates = new ArrayCollection();
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

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getContactPerson(): ?string
    {
        return $this->contactPerson;
    }

    public function setContactPerson(?string $contactPerson): static
    {
        $this->contactPerson = $contactPerson;

        return $this;
    }

    public function getContactEmail(): ?string
    {
        return $this->contactEmail;
    }

    public function setContactEmail(?string $contactEmail): static
    {
        $this->contactEmail = $contactEmail;

        return $this;
    }

    public function getContactPhone(): ?string
    {
        return $this->contactPhone;
    }

    public function setContactPhone(?string $contactPhone): static
    {
        $this->contactPhone = $contactPhone;

        return $this;
    }

    public function getBookingEmail(): ?string
    {
        return $this->bookingEmail;
    }

    public function setBookingEmail(?string $bookingEmail): static
    {
        $this->bookingEmail = $bookingEmail;

        return $this;
    }

    public function getBookingPhone(): ?string
    {
        return $this->bookingPhone;
    }

    public function setBookingPhone(?string $bookingPhone): static
    {
        $this->bookingPhone = $bookingPhone;

        return $this;
    }

    public function isHasWhatsapp(): ?bool
    {
        return $this->hasWhatsapp;
    }

    public function setHasWhatsapp(?bool $hasWhatsapp): static
    {
        $this->hasWhatsapp = $hasWhatsapp;

        return $this;
    }

    public function getInternalNotes(): ?string
    {
        return $this->internalNotes;
    }

    public function setInternalNotes(?string $internalNotes): static
    {
        $this->internalNotes = $internalNotes;

        return $this;
    }

    public function getLegalName(): ?string
    {
        return $this->legalName;
    }

    public function setLegalName(?string $legalName): static
    {
        $this->legalName = $legalName;

        return $this;
    }

    public function getDocumentType(): ?string
    {
        return $this->documentType;
    }

    public function setDocumentType(?string $documentType): static
    {
        $this->documentType = $documentType;

        return $this;
    }

    public function getDocument(): ?string
    {
        return $this->document;
    }

    public function setDocument(?string $document): static
    {
        $this->document = $document;

        return $this;
    }

    public function getAdminEmail(): ?string
    {
        return $this->adminEmail;
    }

    public function setAdminEmail(?string $adminEmail): static
    {
        $this->adminEmail = $adminEmail;

        return $this;
    }

    public function getAdminPhone(): ?string
    {
        return $this->adminPhone;
    }

    public function setAdminPhone(?string $adminPhone): static
    {
        $this->adminPhone = $adminPhone;

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
            $activity->setSupplier($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): static
    {
        if ($this->activities->removeElement($activity)) {
            // set the owning side to null (unless already changed)
            if ($activity->getSupplier() === $this) {
                $activity->setSupplier(null);
            }
        }

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
            $hotel->setSupplier($this);
        }

        return $this;
    }

    public function removeHotel(Hotel $hotel): static
    {
        if ($this->hotels->removeElement($hotel)) {
            // set the owning side to null (unless already changed)
            if ($hotel->getSupplier() === $this) {
                $hotel->setSupplier(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Extra>
     */
    public function getExtras(): Collection
    {
        return $this->extras;
    }

    public function addExtra(Extra $extra): static
    {
        if (!$this->extras->contains($extra)) {
            $this->extras->add($extra);
            $extra->setSupplier($this);
        }

        return $this;
    }

    public function removeExtra(Extra $extra): static
    {
        if ($this->extras->removeElement($extra)) {
            // set the owning side to null (unless already changed)
            if ($extra->getSupplier() === $this) {
                $extra->setSupplier(null);
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
            $communicationTemplate->addSupplier($this);
        }

        return $this;
    }

    public function removeCommunicationTemplate(CommunicationTemplate $communicationTemplate): static
    {
        if ($this->communicationTemplates->removeElement($communicationTemplate)) {
            $communicationTemplate->removeSupplier($this);
        }

        return $this;
    }
}
