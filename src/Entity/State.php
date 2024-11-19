<?php

namespace App\Entity;

use App\Repository\StateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StateRepository::class)]
class State
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $state = null;

    /**
     * @var Collection<int, Unit>
     */
    #[ORM\OneToMany(targetEntity: Unit::class, mappedBy: 'state')]
    private Collection $unit;

    public function __construct()
    {
        $this->unit = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): static
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return Collection<int, Unit>
     */
    public function getUnit(): Collection
    {
        return $this->unit;
    }

    public function addUnit(Unit $unit): static
    {
        if (!$this->unit->contains($unit)) {
            $this->unit->add($unit);
            $unit->setState($this);
        }

        return $this;
    }

    public function removeUnit(Unit $unit): static
    {
        if ($this->unit->removeElement($unit)) {
            // set the owning side to null (unless already changed)
            if ($unit->getState() === $this) {
                $unit->setState(null);
            }
        }

        return $this;
    }
}
