<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ChannelHotelRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ChannelHotelRepository::class)]
#[ApiResource(
    attributes: [
        "order" => ["id" => "DESC"],
        "normalization_context" => ["groups" => ["channelHotelReduced"]]
    ],
    collectionOperations: [
        "get",
        "post",
        // "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => "channelHotel"]],
        "put",
        "delete",
        // "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        // "delete" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class ChannelHotel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['channelHotelReduced', 'channelHotel'])]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['channelHotelReduced', 'channelHotel','hotel', 'hotelReduced'])]
    private ?string $code = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['channelHotelReduced', 'channelHotel'])]
    private ?string $channelCode = null;

    #[ORM\ManyToOne(inversedBy: 'channelHotels')]
    #[Groups(['channelHotelReduced', 'channelHotel'])]
    private ?Hotel $hotel = null;

    #[ORM\ManyToOne(inversedBy: 'channelHotels')]
    #[Groups(['channelHotelReduced', 'channelHotel', 'hotel', 'hotelReduced'])]
    private ?Channel $channel = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getChannelCode(): ?string
    {
        return $this->channelCode;
    }

    public function setChannelCode(?string $channelCode): static
    {
        $this->channelCode = $channelCode;

        return $this;
    }

    public function getHotel(): ?Hotel
    {
        return $this->hotel;
    }

    public function setHotel(?Hotel $hotel): static
    {
        $this->hotel = $hotel;

        return $this;
    }

    public function getChannel(): ?Channel
    {
        return $this->channel;
    }

    public function setChannel(?Channel $channel): static
    {
        $this->channel = $channel;

        return $this;
    }

}
