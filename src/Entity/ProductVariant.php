<?php

namespace App\Entity;

use App\Repository\ProductVariantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductVariantRepository::class)]
class ProductVariant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?float $size = null;

    /**
     * @var Collection<int, Taxe>
     */
    #[ORM\OneToMany(targetEntity: Taxe::class, mappedBy: 'productVariant_id')]
    private Collection $taxe_id;

    #[ORM\ManyToOne(inversedBy: 'productVariants')]
    private ?Product $product_id = null;

    public function __construct()
    {
        $this->taxe_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getSize(): ?float
    {
        return $this->size;
    }

    public function setSize(float $size): static
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return Collection<int, Taxe>
     */
    public function getTaxeId(): Collection
    {
        return $this->taxe_id;
    }

    public function addTaxeId(Taxe $taxeId): static
    {
        if (!$this->taxe_id->contains($taxeId)) {
            $this->taxe_id->add($taxeId);
            $taxeId->setProductVariantId($this);
        }

        return $this;
    }

    public function removeTaxeId(Taxe $taxeId): static
    {
        if ($this->taxe_id->removeElement($taxeId)) {
            // set the owning side to null (unless already changed)
            if ($taxeId->getProductVariantId() === $this) {
                $taxeId->setProductVariantId(null);
            }
        }

        return $this;
    }

    public function getProductId(): ?Product
    {
        return $this->product_id;
    }

    public function setProductId(?Product $product_id): static
    {
        $this->product_id = $product_id;

        return $this;
    }
}
