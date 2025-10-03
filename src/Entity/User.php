<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    private ?string $pseudonyme = null;

    /**
     * @var Collection<int, Flash>
     */
    #[ORM\ManyToMany(targetEntity: Flash::class, inversedBy: 'users')]
    private Collection $flashs;


    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $googleId = null;

    /**
     * @var Collection<int, Tattoo>
     */
    #[ORM\OneToMany(targetEntity: Tattoo::class, mappedBy: 'user')]
    private Collection $tattoos;






    public function __construct()
    {
        $this->flashs = new ArrayCollection();
        $this->tattoos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }

    public function getPseudonyme(): ?string
    {
        return $this->pseudonyme;
    }

    public function setPseudonyme(string $pseudonyme): static
    {
        $this->pseudonyme = $pseudonyme;

        return $this;
    }

    /**
     * @return Collection<int, Flash>
     */
    public function getFlashs(): Collection
    {
        return $this->flashs;
    }

    public function addFlash(Flash $flash): static
    {
        if (!$this->flashs->contains($flash)) {
            $this->flashs->add($flash);
        }

        return $this;
    }

    public function removeFlash(Flash $flash): static
    {
        $this->flashs->removeElement($flash);

        return $this;
    }


    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    public function setGoogleId(?string $googleId): self
    {
        $this->googleId = $googleId;
        return $this;
    }

    /**
     * @return Collection<int, Tattoo>
     */
    public function getTattoos(): Collection
    {
        return $this->tattoos;
    }

    public function addTattoo(Tattoo $tattoo): static
    {
        if (!$this->tattoos->contains($tattoo)) {
            $this->tattoos->add($tattoo);
            $tattoo->setUser($this);
        }

        return $this;
    }

    public function removeTattoo(Tattoo $tattoo): static
    {
        if ($this->tattoos->removeElement($tattoo)) {
            // set the owning side to null (unless already changed)
            if ($tattoo->getUser() === $this) {
                $tattoo->setUser(null);
            }
        }

        return $this;
    }



}
