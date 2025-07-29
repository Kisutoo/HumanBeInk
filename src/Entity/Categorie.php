<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    /**
     * @var Collection<int, Flash>
     */
    #[ORM\OneToMany(targetEntity: Flash::class, mappedBy: 'categorie')]
    private Collection $flash;

    public function __construct()
    {
        $this->flash = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Flash>
     */
    public function getFlash(): Collection
    {
        return $this->flash;
    }

    public function addFlash(Flash $flash): static
    {
        if (!$this->flash->contains($flash)) {
            $this->flash->add($flash);
            $flash->setCategorie($this);
        }

        return $this;
    }

    public function removeFlash(Flash $flash): static
    {
        if ($this->flash->removeElement($flash)) {
            // set the owning side to null (unless already changed)
            if ($flash->getCategorie() === $this) {
                $flash->setCategorie(null);
            }
        }

        return $this;
    }
}
