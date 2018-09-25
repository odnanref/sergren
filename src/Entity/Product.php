<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManager;
use phpDocumentor\Reflection\Types\Null_;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Product
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
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $keywords;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     */
    private $url;

    /**
     * @ORM\Column(type="boolean")
     */
    private $state;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datecreated;
    
    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Category", mappedBy="products",cascade={"persist"})
     */
    private $categories;
    
    /**
     * @var ProductAttribute
     *
     * @ORM\OneToMany(targetEntity="ProductAttribute", mappedBy="product")
     */
    private $productAttributes;
    
    /**
     * @var Media
     *
     * @ORM\OneToMany(targetEntity="Media", mappedBy="product")
     */
    private $medias;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductView", mappedBy="product", orphanRemoval=true)
     */
    private $productViews;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductReportView", mappedBy="Product")
     */
    private $year;
    
    function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->medias = new \Doctrine\Common\Collections\ArrayCollection();$collection;
        $this->productAttributes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->productViews = new ArrayCollection();
        $this->year = new ArrayCollection();
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getCategories(): \Doctrine\Common\Collections\Collection
    {
        return $this->categories;
    }

    /**
     * @return \App\Entity\ProductAttribute
     */
    public function getProductAttributes(): \Doctrine\Common\Collections\Collection
    {
        return $this->productAttributes;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMedias() : \Doctrine\Common\Collections\Collection
    {
        return $this->medias;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $categories
     */
    public function setCategories(ArrayCollection $categories) : self
    {
        $this->categories = $categories;
        return $this;
    }

    /**
     * @param \App\Entity\ProductAttribute $productAttributes
     */
    public function setProductAttributes($productAttributes) : self
    {
        $this->productAttributes = $productAttributes;
        return $this;
    }

    /**
     * @param \App\Entity\Media $medias
     */
    public function setMedias($medias) : self
    {
        $this->medias = $medias;
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    public function setKeywords(?string $keywords): self
    {
        $this->keywords = $keywords;

        return $this;
    }

    public function getState(): ?bool
    {
        return $this->state;
    }

    public function setState(bool $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getDatecreated(): ?\DateTimeInterface
    {
        return $this->datecreated;
    }

    public function setDatecreated(\DateTimeInterface $datecreated): self
    {
        $this->datecreated = $datecreated;

        return $this;
    }
    
    function __toString() {
        return $this->getId() . " " . $this->getName();
    }

    /**
     * @return Collection|ProductView[]
     */
    public function getProductViews(): Collection
    {
        return $this->productViews;
    }

    public function addProductView(ProductView $productView): self
    {
        if (!$this->productViews->contains($productView)) {
            $this->productViews[] = $productView;
            $productView->setProduct($this);
        }

        return $this;
    }

    public function removeProductView(ProductView $productView): self
    {
        if ($this->productViews->contains($productView)) {
            $this->productViews->removeElement($productView);
            // set the owning side to null (unless already changed)
            if ($productView->getProduct() === $this) {
                $productView->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProductReportView[]
     */
    public function getYear(): Collection
    {
        return $this->year;
    }

    public function addYear(ProductReportView $year): self
    {
        if (!$this->year->contains($year)) {
            $this->year[] = $year;
            $year->setProduct($this);
        }

        return $this;
    }

    public function removeYear(ProductReportView $year): self
    {
        if ($this->year->contains($year)) {
            $this->year->removeElement($year);
            // set the owning side to null (unless already changed)
            if ($year->getProduct() === $this) {
                $year->setProduct(null);
            }
        }

        return $this;
    }
    
    public function getUrl(): string
    {
        if ($this->url !== null) {
            return $this->url;
        } else {
            return $this->url = strtolower(trim(str_replace(" ", "-", $this->getName())));   
        }
    }
    
    public function setUrl(?string $url): self
    {
        if (trim($url) == "") {
            $url = $this->getName();
        }
        $this->url = strtolower(trim(str_replace(" ", "-", $url)));
        
        return $this;
    }
    
    /**
     * @ORM\PrePersist
     */
    public function doStuffOnPrePersist()
    {
        $this->url = $this->getUrl();
    }
}
