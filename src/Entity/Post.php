<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:collection']],
    denormalizationContext: ['groups' => ['write:Post']],
    collectionOperation: [
        'get',
        'post' => [
            'validation_groups' => [Post::class, 'validatinGroups']
        ]
    ],
    itemOperations: [
        'put',
        'delete',
        'get' =>[
            'normalization_context' => ['groups' => ['read:collection', 'read:item', 'read:Post']]
        ]
    ]

)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:collection'])]
    private ?int $id;

    #[ORM\Column(length: 255)]
    #[
        Groups(['read:collection', 'write:Post']),
        Length(min: 5, groups: ['create:Post'])
    ]
    private ?string $title;

    #[ORM\Column(length: 255)]
    #[Groups(['read:collection', 'write:Post'])]
    private ?string $slug;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['read:item', 'write:Post'])]
    private ?string $content;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['read:item'])]
    private ?\DateTimeInterface $creaatedAt;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt;

    #[ORM\ManyToOne(inversedBy: 'posts', cascade: "persist")]
    #[Groups('read:item', 'put:Post')]
    private ?Category $category;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreeatedAt(): ?\DateTimeInterface
    {
        return $this->creeatedAt;
    }

    public function setCreeatedAt(\DateTimeInterface $creeatedAt): self
    {
        $this->creeatedAt = $creeatedAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
