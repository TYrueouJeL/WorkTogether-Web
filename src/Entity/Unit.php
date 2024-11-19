<?php

namespace App\Entity;

use App\Repository\UnitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UnitRepository::class)]
class Unit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $reference = null;

    #[ORM\ManyToOne(inversedBy: 'units')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Bay $bay = null;

    #[ORM\ManyToOne(inversedBy: 'units')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usage $usage = null;

    /**
     * @var Collection<int, Intervention>
     */
    #[ORM\OneToMany(targetEntity: Intervention::class, mappedBy: 'unit')]
    private Collection $interventions;

    /**
     * @var Collection<int, CommandedUnit>
     */
    #[ORM\OneToMany(targetEntity: CommandedUnit::class, mappedBy: 'unit')]
    private Collection $commandedUnits;

    #[ORM\ManyToOne(inversedBy: 'unit')]
    private ?State $state = null;

    public function __construct()
    {
        $this->interventions = new ArrayCollection();
        $this->commandedUnits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getBay(): ?Bay
    {
        return $this->bay;
    }

    public function setBay(?Bay $bay): static
    {
        $this->bay = $bay;

        return $this;
    }

    public function getUsage(): ?Usage
    {
        return $this->usage;
    }

    public function setUsage(?Usage $usage): static
    {
        $this->usage = $usage;

        return $this;
    }

    /**
     * @return Collection<int, Intervention>
     */
    public function getInterventions(): Collection
    {
        return $this->interventions;
    }

    public function addIntervention(Intervention $intervention): static
    {
        if (!$this->interventions->contains($intervention)) {
            $this->interventions->add($intervention);
            $intervention->setUnit($this);
        }

        return $this;
    }

    public function removeIntervention(Intervention $intervention): static
    {
        if ($this->interventions->removeElement($intervention)) {
            // set the owning side to null (unless already changed)
            if ($intervention->getUnit() === $this) {
                $intervention->setUnit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommandedUnit>
     */
    public function getCommandedUnits(): Collection
    {
        return $this->commandedUnits;
    }

    public function addCommandedUnit(CommandedUnit $commandedUnit): static
    {
        if (!$this->commandedUnits->contains($commandedUnit)) {
            $this->commandedUnits->add($commandedUnit);
            $commandedUnit->setUnit($this);
        }

        return $this;
    }

    public function removeCommandedUnit(CommandedUnit $commandedUnit): static
    {
        if ($this->commandedUnits->removeElement($commandedUnit)) {
            // set the owning side to null (unless already changed)
            if ($commandedUnit->getUnit() === $this) {
                $commandedUnit->setUnit(null);
            }
        }

        return $this;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): static
    {
        $this->state = $state;

        return $this;
    }
}
