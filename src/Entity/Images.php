<?php

namespace App\Entity;

use App\Repository\ImagesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ImagesRepository::class)
 * @ORM\HasLifecycleCallbacks
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
     * @ORM\JoinColumn(nullable=true)
     */
    private $meal;


    /**
     * @Vich\UploadableField(mapping="meal_file", fileNameProperty="meal")
     * @var File
     */

    private $mealFile;

    /**
     * @Vich\UploadableField(mapping="ingredient_file", fileNameProperty="ingredient")
     * @var File
     */

    private $ingredientFile;

    /**
     * @Vich\UploadableField(mapping="ownerphoto_file", fileNameProperty="ownerphoto")
     * @var File
     */

    private $ownerphotoFile;

    /**
     * @ORM\Column(type="string", length=255)
     * @ORM\JoinColumn(nullable=true)
     */

    private $ingredient;

    /**
     * @ORM\Column(type="string", length=255)
     * @ORM\JoinColumn(nullable=true)
     */
    private $ownerphoto;

    /**
     * @ORM\OneToOne(targetEntity=Episode::class, inversedBy="images", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $episode;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMeal(): ?string
    {
        return $this->meal;
    }

    public function setMeal(?string $meal): self
    {
        $this->meal = $meal;

        return $this;
    }

    public function getIngredient(): ?string
    {
        return $this->ingredient;
    }

    public function setIngredient(?string $ingredient): self
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    public function getOwnerphoto(): ?string
    {
        return $this->ownerphoto;
    }

    public function setOwnerphoto(?string $ownerphoto): self
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

    public function getMealFile(): ?File

    {

        return $this->mealFile;

    }

    public function getIngredientFile(): ?File

    {

        return $this->ingredientFile;

    }

    public function getOwnerphotoFile(): ?File

    {

        return $this->ownerphotoFile;

    }
    public function setMealFile(File $image = null):Images

    {

        $this->mealFile = $image;

        return $this;

    }

    public function setIngredientFile(File $image = null):Images

    {

        $this->ingredientFile = $image;

        return $this;

    }

    public function setOwnerphotoFile(File $image = null):Images

    {

        $this->ownerphotoFile = $image;

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
    /**
     * Gets triggered only on insert
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->updatedAt = new \DateTime();
    }/**
 * Gets triggered only on update
 * @ORM\PreUpdate
 */
    public function onPreUpdate()
    {
        $this->updatedAt = new \DateTime();
    }


}
