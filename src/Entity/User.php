<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $hoten = null;

    #[ORM\Column(length: 100)]
    private ?string $username = null;

    #[ORM\Column(length: 100)]
    private ?string $password = null;

    /**
     * @var Collection<int, Diachi>
     */
    #[ORM\OneToMany(targetEntity: Diachi::class, mappedBy: 'user')]
    private Collection $diachi;

    #[ORM\Column]
    private array $roles = [];

    public function __construct()
    {
        $this->diachi = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHoten(): ?string
    {
        return $this->hoten;
    }

    public function setHoten(string $hoten): static
    {
        $this->hoten = $hoten;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials(): void
    {
        // Xóa thông tin nhạy cảm nếu cần (VD: plain password)
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function addDiachi(Diachi $diachi): static
    {
        if (!$this->diachi->contains($diachi)) {
            $this->diachi->add($diachi);
            $diachi->setUser($this);
        }

        return $this;
    }

    public function removeDiachi(Diachi $diachi): static
    {
        if ($this->diachi->removeElement($diachi)) {
            // set the owning side to null (unless already changed)
            if ($diachi->getUser() === $this) {
                $diachi->setUser(null);
            }
        }

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }
}
