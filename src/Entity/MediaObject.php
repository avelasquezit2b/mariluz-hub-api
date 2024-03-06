<?php
// api/src/Entity/MediaObject.php
namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\CreateMediaObjectAction;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity]
#[ApiResource(
    iri: 'https://schema.org/MediaObject',
    normalizationContext: ['groups' => ['media_object:read']],
    order: ["position" => "ASC"],
    itemOperations: ['get', 'delete', 'put'],
    collectionOperations: [
        'get',
        'post' => [
            'controller' => CreateMediaObjectAction::class,
            'deserialize' => false,
            'validation_groups' => ['Default', 'media_object_create'],
            'openapi_context' => [
                'requestBody' => [
                    'content' => [
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'file' => [
                                        'type' => 'string',
                                        'format' => 'binary',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ]
)]
class MediaObject
{
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    #[Groups(['media_object:read', 'activity', 'hotel', 'roomType', 'theme', 'pack'])]
    private ?int $id = null;

    #[ApiProperty(iri: 'https://schema.org/contentUrl')]
    #[Groups(['media_object:read', 'activity', 'hotelReduced', 'hotel', 'roomType', 'themeReduced', 'theme', 'page', 'heroSlide', 'pack', 'productList'])]
    public ?string $contentUrl = null;

    #[Vich\UploadableField(mapping: "media_object", fileNameProperty: "filePath")]
    #[Assert\NotNull(groups: ['media_object_create'])]
    public ?File $file = null;

    #[ORM\Column(nullable: true)] 
    public ?string $filePath = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['media_object:read', 'activity', 'hotel', 'roomType', 'theme', 'pack', 'productList'])]
    private $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['media_object:read', 'activity', 'hotel', 'roomType', 'theme', 'pack', 'productList'])]
    private $alt;

    #[ORM\ManyToOne(inversedBy: 'media')]
    #[Groups(['media_object:read'])]
    private ?Activity $activity = null;

    #[ORM\Column(length: 25)]
    #[Groups(['media_object:read', 'activity', 'hotel', 'roomType', 'theme', 'pack', 'productList'])]
    private ?string $type = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['media_object:read', 'activity', 'hotel', 'roomType', 'theme', 'pack', 'productList'])]
    private ?int $position = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['media_object:read'])]
    private ?string $supplier = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['media_object:read', 'activity', 'hotel', 'roomType', 'theme', 'pack', 'productList'])]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'media')]
    #[Groups(['media_object:read'])]
    private ?Hotel $hotel = null;

    #[ORM\ManyToOne(inversedBy: 'media')]
    #[Groups(['media_object:read'])]
    private ?RoomType $roomType = null;

    #[ORM\ManyToOne(inversedBy: 'media')]
    private ?HeroModule $heroModule = null;

    #[ORM\ManyToOne(inversedBy: 'media')]
    #[Groups(['media_object:read'])]
    private ?HeroSlide $heroSlide = null;

    #[ORM\ManyToOne(inversedBy: 'media')]
    #[Groups(['media_object:read'])]
    private ?Theme $theme = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Seo $seo = null;

    #[ORM\ManyToOne(inversedBy: 'media')]
    #[Groups(['media_object:read'])]
    private ?Pack $pack = null;

    public function __construct()
    {

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(?string $alt): self
    {
        $this->alt = $alt;

        return $this;
    }

    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    public function setActivity(?Activity $activity): static
    {
        $this->activity = $activity;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getSupplier(): ?string
    {
        return $this->supplier;
    }

    public function setSupplier(?string $supplier): static
    {
        $this->supplier = $supplier;

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

    public function getHotel(): ?Hotel
    {
        return $this->hotel;
    }

    public function setHotel(?Hotel $hotel): static
    {
        $this->hotel = $hotel;

        return $this;
    }

    public function getRoomType(): ?RoomType
    {
        return $this->roomType;
    }

    public function setRoomType(?RoomType $roomType): static
    {
        $this->roomType = $roomType;

        return $this;
    }

    public function getHeroModule(): ?HeroModule
    {
        return $this->heroModule;
    }

    public function setHeroModule(?HeroModule $heroModule): static
    {
        $this->heroModule = $heroModule;

        return $this;
    }

    public function getHeroSlide(): ?HeroSlide
    {
        return $this->heroSlide;
    }

    public function setHeroSlide(?HeroSlide $heroSlide): static
    {
        $this->heroSlide = $heroSlide;

        return $this;
    }

    public function getTheme(): ?Theme
    {
        return $this->theme;
    }

    public function setTheme(?Theme $theme): static
    {
        $this->theme = $theme;

        return $this;
    }

    public function getSeo(): ?Seo
    {
        return $this->seo;
    }

    public function setSeo(?Seo $seo): static
    {
        $this->seo = $seo;

        return $this;
    }

    public function getPack(): ?Pack
    {
        return $this->pack;
    }

    public function setPack(?Pack $pack): static
    {
        $this->pack = $pack;

        return $this;
    }
}