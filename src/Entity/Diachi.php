<?php

namespace App\Entity;

use App\Repository\DiachiRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DiachiRepository::class)]
class Diachi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $diachi = null;

    #[ORM\ManyToOne(inversedBy: 'diachis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Phuong $phuong = null;

    #[ORM\ManyToOne(inversedBy: 'diachis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Quan $quan = null;

    #[ORM\ManyToOne(inversedBy: 'diachis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Thanhpho $thanhpho = null;

    #[ORM\ManyToOne(inversedBy: 'diachis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiachi(): ?string
    {
        return $this->diachi;
    }

    public function setDiachi(string $diachi): static
    {
        $this->diachi = $diachi;

        return $this;
    }

    public function getPhuong(): ?Phuong
    {
        return $this->phuong;
    }

    public function setPhuong(?Phuong $phuong): static
    {
        $this->phuong = $phuong;

        return $this;
    }

    public function getQuan(): ?Quan
    {
        return $this->quan;
    }

    public function setQuan(?Quan $quan): static
    {
        $this->quan = $quan;

        return $this;
    }

    public function getThanhpho(): ?Thanhpho
    {
        return $this->thanhpho;
    }

    public function setThanhpho(?Thanhpho $thanhpho): static
    {
        $this->thanhpho = $thanhpho;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

}
