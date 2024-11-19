<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column]
    private ?bool $isAnnual = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pack $pack = null;

    /**
     * @var Collection<int, CommandedUnit>
     */
    #[ORM\OneToMany(targetEntity: CommandedUnit::class, mappedBy: 'orders')]
    private Collection $commandedUnits;

    public function __construct()
    {
        $this->commandedUnits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function isAnnual(): ?bool
    {
        return $this->isAnnual;
    }

    public function setAnnual(bool $isAnnual): static
    {
        $this->isAnnual = $isAnnual;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getPack(): ?Pack
    {
        return $this->pack;
    }

    public function setPack(?Pack $pack): static
    {
        $this->pack = $pack;

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
            $commandedUnit->setOrders($this);
        }

        return $this;
    }

    public function removeCommandedUnit(CommandedUnit $commandedUnit): static
    {
        if ($this->commandedUnits->removeElement($commandedUnit)) {
            // set the owning side to null (unless already changed)
            if ($commandedUnit->getOrders() === $this) {
                $commandedUnit->setOrders(null);
            }
        }

        return $this;
    }
}
