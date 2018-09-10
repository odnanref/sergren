<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductReportViewRepository")
 */
class ProductReportView
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="year")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Product;

    /**
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * @ORM\Column(type="integer")
     */
    private $month;

    /**
     * @ORM\Column(type="integer")
     */
    private $totalViews;

    /**
     * @ORM\Column(type="integer")
     */
    private $totalByDisctinctIp;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->Product;
    }

    public function setProduct(?Product $Product): self
    {
        $this->Product = $Product;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getMonth(): ?int
    {
        return $this->month;
    }

    public function setMonth(int $month): self
    {
        $this->month = $month;

        return $this;
    }

    public function getTotalViews(): ?int
    {
        return $this->totalViews;
    }

    public function setTotalViews(int $totalViews): self
    {
        $this->totalViews = $totalViews;

        return $this;
    }

    public function getTotalByDisctinctIp(): ?int
    {
        return $this->totalByDisctinctIp;
    }

    public function setTotalByDisctinctIp(int $totalByDisctinctIp): self
    {
        $this->totalByDisctinctIp = $totalByDisctinctIp;

        return $this;
    }
}
