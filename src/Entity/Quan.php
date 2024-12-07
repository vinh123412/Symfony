<?php

namespace App\Entity;

use App\Repository\QuanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuanRepository::class)]
class Quan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $tenQuan = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTenQuan(): ?string
    {
        return $this->tenQuan;
    }

    public function setTenQuan(string $tenQuan): static
    {
        $this->tenQuan = $tenQuan;

        return $this;
    }

}
