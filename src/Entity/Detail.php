<?php

namespace App\Entity;

use App\Repository\DetailRepository;
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

        public function __toString(): string
    {
        return $this->getDetailName();
    }
}
