<?php

namespace App\Entity;

use App\Repository\TaxeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaxeRepository::class)]
class Taxe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $rate = null;

    #[ORM\Column]
    private ?bool $enable = null;

    #[ORM\ManyToOne(inversedBy: 'taxe_id')]
    private ?ProductVariant $productVariant_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(float $rate): static
    {
        $this->rate = $rate;

        return $this;
    }

    public function isEnable(): ?bool
    {
        return $this->enable;
    }

    public function setEnable(bool $enable): static
    {
        $this->enable = $enable;

        return $this;
    }

    public function getProductVariantId(): ?ProductVariant
    {
        return $this->productVariant_id;
    }

    public function setProductVariantId(?ProductVariant $productVariant_id): static
    {
        $this->productVariant_id = $productVariant_id;

        return $this;
    }
}
