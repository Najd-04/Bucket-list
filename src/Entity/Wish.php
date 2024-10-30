<?php

namespace App\Entity;

use App\Repository\WishRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WishRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Wish
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 250)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $decription = null;

    #[ORM\Column(length: 50)]
    private ?string $author = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isPublished = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreated = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateUpdated = null;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'wishes')]
    private Collection $category;

    #[ORM\ManyToOne(inversedBy: 'wishes')]
    private ?User $user = null;

    /**
     * @var Collection<int, Commentaire>
     */
    #[ORM\OneToMany(targetEntity: Commentaire::class, mappedBy: 'wish')]
    private Collection $commentaires;

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
    }



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

    public function getDecription(): ?string
    {
        return $this->decription;
    }

    public function setDecription(?string $decription): static
    {
        $this->decription = $decription;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setPublished(?bool $isPublished): static
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }
  #[ORM\PrePersist]
    public function setDateCreated(): static
    {
        $this->dateCreated = new \DateTime();

        return $this;
    }

    public function getDateUpdated(): ?\DateTimeInterface
    {
        return $this->dateUpdated;
    }
  #[ORM\PreUpdate]
    public function setDateUpdated(): static
    {
        $this->dateUpdated = new \DateTime();

        return $this;
    }

  /**
   * @return Collection<int, Category>
   */
  public function getCategory(): Collection
  {
      return $this->category;
  }

  public function addCategory(Category $category): static
  {
      if (!$this->category->contains($category)) {
          $this->category->add($category);
      }

      return $this;
  }

  public function removeCategory(Category $category): static
  {
      $this->category->removeElement($category);

      return $this;
  }

  public function getUser(): ?User
  {
      return $this->user;
  }

  public function setUser(?User $user): static
  {
      $this->user = $user;

      return $this;
  }

  /**
   * @return Collection<int, Commentaire>
   */
  public function getCommentaires(): Collection
  {
      return $this->commentaires;
  }

  public function addCommentaire(Commentaire $commentaire): static
  {
      if (!$this->commentaires->contains($commentaire)) {
          $this->commentaires->add($commentaire);
          $commentaire->setWish($this);
      }

      return $this;
  }

  public function removeCommentaire(Commentaire $commentaire): static
  {
      if ($this->commentaires->removeElement($commentaire)) {
          // set the owning side to null (unless already changed)
          if ($commentaire->getWish() === $this) {
              $commentaire->setWish(null);
          }
      }

      return $this;
  }

}
