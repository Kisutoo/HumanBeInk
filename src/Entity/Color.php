<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ColorRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: ColorRepository::class)]
class Color
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $typeColor = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $multiplicator = null;

    /**
     * @var Collection<int, tattoo>
     */
    #[ORM\OneToMany(targetEntity: Tattoo::class, mappedBy: 'color')]
    private Collection $tattoo;

    public function __construct()
    {
        $this->tattoo = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeColor(): ?string
    {
        return $this->typeColor;
    }

    public function setTypeColor(string $typeColor): static
    {
        $this->typeColor = $typeColor;

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
            $tattoo->setColor($this);
        }

        return $this;
    }

    public function removeTattoo(Tattoo $tattoo): static
    {
        if ($this->tattoo->removeElement($tattoo)) {
            // set the owning side to null (unless already changed)
            if ($tattoo->getColor() === $this) {
                $tattoo->setColor(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getTypeColor();
    }
}
