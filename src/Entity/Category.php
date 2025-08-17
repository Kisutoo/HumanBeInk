<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    /**
     * @var Collection<int, Flash>
     */
    #[ORM\OneToMany(targetEntity: Flash::class, mappedBy: 'category')]
    private Collection $flash;

    public function __construct()
    {
        $this->flash = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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
            $flash->setCategory($this);
        }

        return $this;
    }

    public function removeFlash(Flash $flash): static
    {
        if ($this->flash->removeElement($flash)) {
            // set the owning side to null (unless already changed)
            if ($flash->getCategory() === $this) {
                $flash->setCategory(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}
