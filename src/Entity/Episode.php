<?php

namespace App\Entity;

use App\Repository\EpisodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EpisodeRepository::class)
 */
class Episode
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     */
    private $number;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $recipename;

    /**
     * @ORM\Column(type="time")
     */
    private $preparationtime;

    /**
     * @ORM\Column(type="integer")
     */
    private $person;

    /**
     * @ORM\OneToOne(targetEntity=Preparation::class, mappedBy="episode", cascade={"persist", "remove"})
     */
    private $preparation;

    /**
     * @ORM\ManyToOne(targetEntity=Season::class, inversedBy="episodes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $season;

    /**
     * @ORM\OneToOne(targetEntity=Images::class, mappedBy="episode", cascade={"persist", "remove"})
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity=ListIngredient::class, mappedBy="episode", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $listIngredients;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    public function __construct()
    {
        $this->listIngredients = new ArrayCollection();
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

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getRecipename(): ?string
    {
        return $this->recipename;
    }

    public function setRecipename(string $recipename): self
    {
        $this->recipename = $recipename;

        return $this;
    }

    public function getPreparationtime(): ?\DateTimeInterface
    {
        return $this->preparationtime;
    }

    public function setPreparationtime(\DateTimeInterface $preparationtime): self
    {
        $this->preparationtime = $preparationtime;

        return $this;
    }

    public function getPerson(): ?int
    {
        return $this->person;
    }

    public function setPerson(int $person): self
    {
        $this->person = $person;

        return $this;
    }

    public function getPreparation(): ?Preparation
    {
        return $this->preparation;
    }

    public function setPreparation(Preparation $preparation): self
    {
        // set the owning side of the relation if necessary
        if ($preparation->getEpisode() !== $this) {
            $preparation->setEpisode($this);
        }

        $this->preparation = $preparation;

        return $this;
    }

    public function getSeason(): ?Season
    {
        return $this->season;
    }

    public function setSeason(?Season $season): self
    {
        $this->season = $season;

        return $this;
    }

    public function getImages(): ?Images
    {
        return $this->images;
    }

    public function setImages(Images $images): self
    {
        // set the owning side of the relation if necessary
        if ($images->getEpisode() !== $this) {
            $images->setEpisode($this);
        }

        $this->images = $images;

        return $this;
    }

    /**
     * @return Collection|ListIngredient[]
     */
    public function getListIngredients(): Collection
    {
        return $this->listIngredients;
    }

    public function addListIngredient(ListIngredient $listIngredient): self
    {
        if (!$this->listIngredients->contains($listIngredient)) {
            $this->listIngredients[] = $listIngredient;
            $listIngredient->setEpisode($this);
        }

        return $this;
    }

    public function removeListIngredient(ListIngredient $listIngredient): self
    {
        if ($this->listIngredients->removeElement($listIngredient)) {
            // set the owning side to null (unless already changed)
            if ($listIngredient->getEpisode() === $this) {
                $listIngredient->setEpisode(null);
            }
        }

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
}
