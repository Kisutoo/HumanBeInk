<?php

namespace App\Entity;

use App\Repository\DetailRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DetailRepository::class)]
class Detail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $detailName = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $multiplicator = null;

    /**
     * @var Collection<int, Tattoo>
     */
    #[ORM\OneToMany(targetEntity: Tattoo::class, mappedBy: 'detail')]
    private Collection $tattoo;

    public function __construct()
    {
        $this->tattoo = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDetailName(): ?string
    {
        return $this->detailName;
    }

    public function setDetailName(string $detailName): static
    {
        $this->detailName = $detailName;

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
     * @return Collection<int, Tattoo>
     */
    public function getTattoo(): Collection
    {
        return $this->tattoo;
    }

    public function addTattoo(Tattoo $tattoo): static
    {
        if (!$this->tattoo->contains($tattoo)) {
            $this->tattoo->add($tattoo);
            $tattoo->setDetail($this);
        }

        return $this;
    }

    public function removeTattoo(Tattoo $tattoo): static
    {
        if ($this->tattoo->removeElement($tattoo)) {
            // set the owning side to null (unless already changed)
            if ($tattoo->getDetail() === $this) {
                $tattoo->setDetail(null);
            }
        }
        return $this;
    }

    public function __toString(): string
    {
        return $this->getDetailName();
    }
}
