<?php

namespace App\Entity;

use App\Repository\PhuongRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PhuongRepository::class)]
class Phuong
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $tenPhuong = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTenPhuong(): ?string
    {
        return $this->tenPhuong;
    }

    public function setTenPhuong(string $tenPhuong): static
    {
        $this->tenPhuong = $tenPhuong;

        return $this;
    }

}
