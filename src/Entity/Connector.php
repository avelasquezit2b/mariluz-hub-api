<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ConnectorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ConnectorRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "DESC"],
        "normalization_context" => ["groups" => ["connectorReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "connector"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class Connector
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['connectorReduced', 'connector'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['connectorReduced', 'connector'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['connectorReduced', 'connector'])]
    private ?string $connectorKey = null;

    #[ORM\OneToMany(mappedBy: 'connector', targetEntity: Channel::class)]
    #[Groups(['connectorReduced', 'connector'])]
    private Collection $channels;

    public function __construct()
    {
        $this->channels = new ArrayCollection();
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

    public function getConnectorKey(): ?string
    {
        return $this->connectorKey;
    }

    public function setConnectorKey(?string $connectorKey): static
    {
        $this->connectorKey = $connectorKey;

        return $this;
    }

    /**
     * @return Collection<int, Channel>
     */
    public function getChannels(): Collection
    {
        return $this->channels;
    }

    public function addChannel(Channel $channel): static
    {
        if (!$this->channels->contains($channel)) {
            $this->channels->add($channel);
            $channel->setConnector($this);
        }

        return $this;
    }

    public function removeChannel(Channel $channel): static
    {
        if ($this->channels->removeElement($channel)) {
            // set the owning side to null (unless already changed)
            if ($channel->getConnector() === $this) {
                $channel->setConnector(null);
            }
        }

        return $this;
    }
}
