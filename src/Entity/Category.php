<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $keywords;
    
    /**
     * Identifies the products
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Product", inversedBy="categories",cascade={"persist"})
     */
    protected $products;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     */
    private $url;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CategoryView", mappedBy="Category")
     */
    private $categoryViews;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CategoryReportView", mappedBy="Category", orphanRemoval=true)
     */
    private $categoryReportViews;
    
    /**
     * @return string
     */
    public function getUrl() : string
    {
        if ($this->url !== null) {
            return $this->url;
        } else {
            return $this->url = strtolower(trim(str_replace(" ", "-", $this->getName())));
        }
    }

    /**
     * @param string|null $url
     */
    public function setUrl(?string $url) : self
    {
        if (trim($url) == "") {
            $url = $this->getName();
        }
        $this->url = strtolower(trim(str_replace(" ", "-", $url)));
        
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getProducts() : \Doctrine\Common\Collections\Collection
    {
        return $this->products;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $products
     */
    public function setProducts($products): self
    {
        $this->products = $products;
        return $this;
    }

    function __construct(){
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
        $this->categoryViews = new ArrayCollection();
        $this->categoryReportViews = new ArrayCollection();
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

    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    public function setKeywords(?string $keywords): self
    {
        $this->keywords = $keywords;

        return $this;
    }
    
    function __toString() {
        return $this->getId() . " " . $this->getName();
    }

    /**
     * @return Collection|CategoryView[]
     */
    public function getDatecreated(): Collection
    {
        return $this->datecreated;
    }

    public function addDatecreated(CategoryView $datecreated): self
    {
        if (!$this->categoryViews->contains($datecreated)) {
            $this->categoryViews[] = $datecreated;
            $datecreated->setCategory($this);
        }

        return $this;
    }

    public function removeDatecreated(CategoryView $categoryView): self
    {
        if ($this->categoryViews->contains($datecreated)) {
            $this->categoryViews->removeElement($datecreated);
            // set the owning side to null (unless already changed)
            if ($categoryView->getCategory() === $this) {
                $categoryView->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CategoryReportView[]
     */
    public function getCategoryReportViews(): Collection
    {
        return $this->categoryReportViews;
    }

    public function addCategoryReportView(CategoryReportView $categoryReportView): self
    {
        if (!$this->categoryReportViews->contains($categoryReportView)) {
            $this->categoryReportViews[] = $categoryReportView;
            $categoryReportView->setCategory($this);
        }

        return $this;
    }

    public function removeCategoryReportView(CategoryReportView $categoryReportView): self
    {
        if ($this->categoryReportViews->contains($categoryReportView)) {
            $this->categoryReportViews->removeElement($categoryReportView);
            // set the owning side to null (unless already changed)
            if ($categoryReportView->getCategory() === $this) {
                $categoryReportView->setCategory(null);
            }
        }

        return $this;
    }
}
