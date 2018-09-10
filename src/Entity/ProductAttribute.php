<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductAttributeRepository")
 */
class ProductAttribute
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $value;
    
    /**
     * This can be hidden, text , datetime, date - for now only hidden will be used
     * 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="productAttributes", cascade={"persist"})
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }
    
    public function setProduct(Product $product): self 
    {
        $this->product = $product;
        return $this;
    }
    
    public function getProduct(): ?Product {
        return $this->product;
    }
    
    public function setType(?string $type): self
    {
        $this->type = $type;
        return $this;
    }
    
    public function getType(): ?string {
        return $this->type;
    }
}
