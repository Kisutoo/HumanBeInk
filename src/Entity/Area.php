<?php

namespace App\Entity;

use App\Repository\AreaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AreaRepository::class)]
class Area
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nameArea = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $sensibility = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $multiplicator = null;

    /**
     * @var Collection<int, tattoo>
     */
    #[ORM\OneToMany(targetEntity: Tattoo::class, mappedBy: 'area')]
    private Collection $tattoo;

    public function __construct()
    {
        $this->tattoo = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameArea(): ?string
    {
        return $this->nameArea;
    }

    public function setNameArea(string $nameArea): static
    {
        $this->nameArea = $nameArea;

        return $this;
    }

    public function getSensibility(): ?int
    {
        return $this->sensibility;
    }

    public function setSensibility(int $sensibility): static
    {
        $this->sensibility = $sensibility;

        return $this;
    }

    public function getMultiplicator(): ?string
    {
        return $this->multiplicator;
    }

    public function setMultiplicator(string $multiplicator): static
    {
        $this->multiplicator = $multiplicator;

        return $this;
    }

    /**
     * @return Collection<int, tattoo>
     */
    public function getTattoo(): Collection
    {
        return $this->tattoo;
    }

    public function addTattoo(Tattoo $tattoo): static
    {
        if (!$this->tattoo->contains($tattoo)) {
            $this->tattoo->add($tattoo);
            $tattoo->setArea($this);
        }

        return $this;
    }

    public function removeTattoo(Tattoo $tattoo): static
    {
        if ($this->tattoo->removeElement($tattoo)) {
            // set the owning side to null (unless already changed)
            if ($tattoo->getArea() === $this) {
                $tattoo->setArea(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getNameArea();
    }
}
