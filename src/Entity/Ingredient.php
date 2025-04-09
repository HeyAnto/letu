<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    /**
     * @var Collection<int, Step>
     */
    #[ORM\ManyToMany(targetEntity: Step::class, mappedBy: 'ingredient')]
    private Collection $steps;

    /**
     * @var Collection<int, Quantity>
     */
    #[ORM\OneToMany(targetEntity: Quantity::class, mappedBy: 'ingredient')]
    private Collection $quantity;

    public function __construct()
    {
        $this->steps = new ArrayCollection();
        $this->quantity = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Step>
     */
    public function getSteps(): Collection
    {
        return $this->steps;
    }

    public function addStep(Step $step): static
    {
        if (!$this->steps->contains($step)) {
            $this->steps->add($step);
            $step->addIngredient($this);
        }

        return $this;
    }

    public function removeStep(Step $step): static
    {
        if ($this->steps->removeElement($step)) {
            $step->removeIngredient($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Quantity>
     */
    public function getQuantity(): Collection
    {
        return $this->quantity;
    }

    public function addQuantity(Quantity $quantity): static
    {
        if (!$this->quantity->contains($quantity)) {
            $this->quantity->add($quantity);
            $quantity->setIngredient($this);
        }

        return $this;
    }

    public function removeQuantity(Quantity $quantity): static
    {
        if ($this->quantity->removeElement($quantity)) {
            // set the owning side to null (unless already changed)
            if ($quantity->getIngredient() === $this) {
                $quantity->setIngredient(null);
            }
        }

        return $this;
    }
}
