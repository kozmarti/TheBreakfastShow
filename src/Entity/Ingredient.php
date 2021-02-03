<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IngredientRepository::class)
 */
class Ingredient
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
    private $name;

    /**
     * @ORM\OneToOne(targetEntity=FunFact::class, mappedBy="ingredient", cascade={"persist", "remove"})
     */
    private $funFact;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFunFact(): ?FunFact
    {
        return $this->funFact;
    }

    public function setFunFact(FunFact $funFact): self
    {
        // set the owning side of the relation if necessary
        if ($funFact->getIngredient() !== $this) {
            $funFact->setIngredient($this);
        }

        $this->funFact = $funFact;

        return $this;
    }
}
