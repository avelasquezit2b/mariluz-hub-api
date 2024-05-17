<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ChannelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ChannelRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "DESC"],
        "normalization_context" => ["groups" => ["channelReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "channel"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class Channel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['channelReduced', 'channel'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['channelReduced', 'channel'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['channelReduced', 'channel', 'hotel', 'hotelReduced'])]
    private ?string $channelKey = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['channelReduced', 'channel'])]
    private ?string $username = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['channelReduced', 'channel'])]
    private ?string $password = null;

    #[ORM\ManyToOne(inversedBy: 'channels')]
    private ?Connector $connector = null;

    #[ORM\OneToMany(mappedBy: 'channel', targetEntity: ChannelHotel::class)]
    #[Groups(['channelReduced', 'channel'])]
    private Collection $channelHotels;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['channelReduced', 'channel', 'hotel', 'hotelReduced'])]
    private ?string $code = null;

    public function __construct()
    {
        $this->channelHotels = new ArrayCollection();
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

    public function getChannelKey(): ?string
    {
        return $this->channelKey;
    }

    public function setChannelKey(?string $channelKey): static
    {
        $this->channelKey = $channelKey;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getConnector(): ?Connector
    {
        return $this->connector;
    }

    public function setConnector(?Connector $connector): static
    {
        $this->connector = $connector;

        return $this;
    }

    /**
     * @return Collection<int, ChannelHotel>
     */
    public function getChannelHotels(): Collection
    {
        return $this->channelHotels;
    }

    public function addChannelHotel(ChannelHotel $channelHotel): static
    {
        if (!$this->channelHotels->contains($channelHotel)) {
            $this->channelHotels->add($channelHotel);
            $channelHotel->setChannel($this);
        }

        return $this;
    }

    public function removeChannelHotel(ChannelHotel $channelHotel): static
    {
        if ($this->channelHotels->removeElement($channelHotel)) {
            // set the owning side to null (unless already changed)
            if ($channelHotel->getChannel() === $this) {
                $channelHotel->setChannel(null);
            }
        }

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

}
