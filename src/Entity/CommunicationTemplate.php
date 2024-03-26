<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CommunicationTemplateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommunicationTemplateRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "ASC"],
        "normalization_context" => ["groups" => ["communicationTemplateReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "communicationTemplate"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class CommunicationTemplate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['communicationTemplateReduced', 'communicationTemplate'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['communicationTemplateReduced', 'communicationTemplate'])]
    private ?string $type = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['communicationTemplateReduced', 'communicationTemplate'])]
    private ?string $content = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['communicationTemplateReduced', 'communicationTemplate'])]
    private ?string $templateName = null;

    #[ORM\ManyToMany(targetEntity: Supplier::class, inversedBy: 'communicationTemplates')]
    #[Groups(['communicationTemplateReduced', 'communicationTemplate'])]
    private Collection $supplier;

    #[ORM\ManyToMany(targetEntity: Client::class, inversedBy: 'communicationTemplates')]
    #[Groups(['communicationTemplateReduced', 'communicationTemplate'])]
    private Collection $client;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['communicationTemplateReduced', 'communicationTemplate'])]
    private ?string $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['communicationTemplateReduced', 'communicationTemplate'])]
    private ?string $subject = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    #[Groups(['communicationTemplateReduced', 'communicationTemplate'])]
    private ?array $recipients = null;

    public function __construct()
    {
        $this->client = new ArrayCollection();
        $this->supplier = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getTemplateName(): ?string
    {
        return $this->templateName;
    }

    public function setTemplateName(?string $templateName): static
    {
        $this->templateName = $templateName;

        return $this;
    }

    /**
     * @return Collection<int, Client>
     */
    public function getSupplier(): Collection
    {
        return $this->supplier;
    }

    public function addSupplier(Supplier $supplier): static
    {
        if (!$this->supplier->contains($supplier)) {
            $this->supplier->add($supplier);
        }

        return $this;
    }

    public function removeSupplier(Supplier $supplier): static
    {
        $this->supplier->removeElement($supplier);

        return $this;
    }

    /**
     * @return Collection<int, Client>
     */
    public function getClient(): Collection
    {
        return $this->client;
    }

    public function addClient(Client $client): static
    {
        if (!$this->client->contains($client)) {
            $this->client->add($client);
        }

        return $this;
    }

    public function removeClient(Client $client): static
    {
        $this->client->removeElement($client);

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getRecipients(): ?array
    {
        return $this->recipients;
    }

    public function setRecipients(?array $recipients): static
    {
        $this->recipients = $recipients;

        return $this;
    }
}
