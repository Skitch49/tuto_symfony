<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use App\Validator\BanWord;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[UniqueEntity('title',message:'Ce titre est déjà utilisé.')]
#[UniqueEntity('slug',message:'Ce slug est déjà utilisé.')]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 5,
        minMessage: 'Votre titre est trop court.',
    )]
    #[BanWord()]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 5, minMessage: 'Votre slug est trop court.')]
    #[Assert\Regex("/^[a-z0-9]+(?:-[a-z0-9]+)*$/i", message: 'Votre slug est invalide.')]
    #[BanWord()]
    private ?string $slug = null;

    #[ORM\Column(type: 'text')]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank()] // Pour que la valeur ne soit pas null
    #[Assert\Positive(message: 'Votre durée doit être positive.')]
    #[Assert\LessThan(value:1440,message:'Votre durée dépasse les 24h !')]
    private ?int $duration = null;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    private ?Category $category = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }
}
