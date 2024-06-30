<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_created = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_expired = null;

    #[ORM\Column]
    private ?bool $multiple = null;

    #[ORM\ManyToOne(inversedBy: 'Projects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, ProjectCategories>
     */
    #[ORM\OneToMany(targetEntity: ProjectCategories::class, mappedBy: 'projects')]
    private Collection $Categories;

    /**
     * @var Collection<int, RewardsDefault>
     */
    #[ORM\ManyToMany(targetEntity: RewardsDefault::class, inversedBy: 'projects')]
    private Collection $RewardsDefault;

    /**
     * @var Collection<int, RewardsIndividual>
     */
    #[ORM\OneToOne(targetEntity: RewardsIndividual::class, inversedBy: 'projects')]
    private Collection $RewardsIndividual;

    #[ORM\OneToOne(mappedBy: 'project', cascade: ['persist', 'remove'])]
    private ?Comments $comments = null;

    /**
     * @var Collection<int, Reviews>
     */
    #[ORM\OneToMany(targetEntity: Reviews::class, mappedBy: 'Project', orphanRemoval: true)]
    private Collection $reviews;

    public function __construct()
    {
        $this->Categories = new ArrayCollection();
        $this->RewardsDefault = new ArrayCollection();
        $this->RewardsIndividual = new ArrayCollection();
        $this->reviews = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->date_created;
    }

    public function setDateCreated(\DateTimeInterface $date_created): static
    {
        $this->date_created = $date_created;

        return $this;
    }

    public function getDateExpired(): ?\DateTimeInterface
    {
        return $this->date_expired;
    }

    public function setDateExpired(?\DateTimeInterface $date_expired): static
    {
        $this->date_expired = $date_expired;

        return $this;
    }

    public function isMultiple(): ?bool
    {
        return $this->multiple;
    }

    public function setMultiple(bool $multiple): static
    {
        $this->multiple = $multiple;

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
     * @return Collection<int, ProjectCategories>
     */
    public function getCategories(): Collection
    {
        return $this->Categories;
    }

    public function addCategory(ProjectCategories $category): static
    {
        if (!$this->Categories->contains($category)) {
            $this->Categories->add($category);
            $category->setProjects($this);
        }

        return $this;
    }

    public function removeCategory(ProjectCategories $category): static
    {
        if ($this->Categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getProjects() === $this) {
                $category->setProjects(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RewardsDefault>
     */
    public function getRewardsDefault(): Collection
    {
        return $this->RewardsDefault;
    }

    public function addRewardsDefault(RewardsDefault $rewardsDefault): static
    {
        if (!$this->RewardsDefault->contains($rewardsDefault)) {
            $this->RewardsDefault->add($rewardsDefault);
        }

        return $this;
    }

    public function removeRewardsDefault(RewardsDefault $rewardsDefault): static
    {
        $this->RewardsDefault->removeElement($rewardsDefault);

        return $this;
    }

    /**
     * @return Collection<int, RewardsIndividual>
     */
    public function getRewardsIndividual(): Collection
    {
        return $this->RewardsIndividual;
    }

    public function addRewardsIndividual(RewardsIndividual $rewardsIndividual): static
    {
        if (!$this->RewardsIndividual->contains($rewardsIndividual)) {
            $this->RewardsIndividual->add($rewardsIndividual);
        }

        return $this;
    }

    public function removeRewardsIndividual(RewardsIndividual $rewardsIndividual): static
    {
        $this->RewardsIndividual->removeElement($rewardsIndividual);

        return $this;
    }

    public function getComments(): ?Comments
    {
        return $this->comments;
    }

    public function setComments(Comments $comments): static
    {
        // set the owning side of the relation if necessary
        if ($comments->getProject() !== $this) {
            $comments->setProject($this);
        }

        $this->comments = $comments;

        return $this;
    }

    /**
     * @return Collection<int, Reviews>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Reviews $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setProject($this);
        }

        return $this;
    }

    public function removeReview(Reviews $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getProject() === $this) {
                $review->setProject(null);
            }
        }

        return $this;
    }
}
