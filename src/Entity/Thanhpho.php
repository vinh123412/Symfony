<?php

namespace App\Entity;

use App\Repository\ThanhphoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ThanhphoRepository::class)]
class Thanhpho
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $tenThanhpho = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTenThanhpho(): ?string
    {
        return $this->tenThanhpho;
    }

    public function setTenThanhpho(string $tenThanhpho): static
    {
        $this->tenThanhpho = $tenThanhpho;

        return $this;
    }


}
