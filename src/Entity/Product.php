<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $name = null;

    #[ORM\Column(length: 20)]
    private ?string $unit = null;

    #[ORM\Column(nullable: true)]
    private ?int $parcelsPerPallet = null;

    #[ORM\Column]
    private ?int $minStock = null;

    #[ORM\Column]
    private ?int $currentStock = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\Column(length: 50, unique: true)]
    private ?string $sku = null;

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

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): static
    {
        $this->unit = $unit;

        return $this;
    }

    public function getParcelsPerPallet(): ?int
    {
        return $this->parcelsPerPallet;
    }

    public function setParcelsPerPallet(?int $parcelsPerPallet): static
    {
        $this->parcelsPerPallet = $parcelsPerPallet;

        return $this;
    }

    public function getMinStock(): ?int
    {
        return $this->minStock;
    }

    public function setMinStock(int $minStock): static
    {
        $this->minStock = $minStock;

        return $this;
    }

    public function getCurrentStock(): ?int
    {
        return $this->currentStock;
    }

    public function setCurrentStock(int $currentStock): static
    {
        $this->currentStock = $currentStock;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(string $sku): static
    {
        $this->sku = $sku;

        return $this;
    }
}
