<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MediaRepository")
 */
class Media
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
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="medias", cascade={"persist"})
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    /**
     * @return mixed
     */
    public function getProduct() : ?Product
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     */
    public function setProduct(Product $product): self
    {
        $this->product = $product;
        return $this;
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * This is actually the filename not the full path
     * 
     * @return string|NULL
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }
    
    function getThumb(): string {
        $tmp = explode(".", $this->getPath());
        if ( count($tmp) <=1 ) {
            throw new \Exception("Unable to see extension in filename");
        }
        
        $extension = $tmp[count($tmp)-1];
        $image_thumb = \str_replace("." . $extension, "_thumb." . $extension, $this->getPath());
        return $image_thumb;
    }
}
