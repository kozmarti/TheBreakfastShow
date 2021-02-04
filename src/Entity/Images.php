<?php

namespace App\Entity;

use App\Repository\ImagesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ImagesRepository::class)
 * @Vich\Uploadable
 */
class Images
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
    private $meal;

    /**
     * @Vich\UploadableField(mapping="meal_file", fileNameProperty="meal")
     * @var File
     */

    private $mealFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ingredient;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ownerphoto;

    /**
     * @ORM\OneToOne(targetEntity=Episode::class, inversedBy="images", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $episode;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMeal(): ?string
    {
        return $this->meal;
    }

    public function setMeal(string $meal): self
    {
        $this->meal = $meal;

        return $this;
    }

    public function getIngredient(): ?string
    {
        return $this->ingredient;
    }

    public function setIngredient(string $ingredient): self
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    public function getOwnerphoto(): ?string
    {
        return $this->ownerphoto;
    }

    public function setOwnerphoto(string $ownerphoto): self
    {
        $this->ownerphoto = $ownerphoto;

        return $this;
    }

    public function getEpisode(): ?Episode
    {
        return $this->episode;
    }

    public function setEpisode(Episode $episode): self
    {
        $this->episode = $episode;

        return $this;
    }
}
