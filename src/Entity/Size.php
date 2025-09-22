<?php

namespace App\Entity;

use App\Repository\SizeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SizeRepository::class)]
class Size
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $size = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $multiplicator = null;

    /**
     * @var Collection<int, tattoo>
     */
    #[ORM\OneToMany(targetEntity: tattoo::class, mappedBy: 'size')]
    private Collection $tattoo;

    public function __construct()
    {
        $this->tattoo = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(string $size): static
    {
        $this->size = $size;

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

    public function addTattoo(tattoo $tattoo): static
    {
        if (!$this->tattoo->contains($tattoo)) {
            $this->tattoo->add($tattoo);
            $tattoo->setSize($this);
        }

        return $this;
    }

    public function removeTattoo(tattoo $tattoo): static
    {
        if ($this->tattoo->removeElement($tattoo)) {
            // set the owning side to null (unless already changed)
            if ($tattoo->getSize() === $this) {
                $tattoo->setSize(null);
            }
        }

        return $this;
    }
}
