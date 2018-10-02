<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryReportViewRepository")
 */
class CategoryReportView
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="categoryReportViews")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Category;

    /**
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * @ORM\Column(type="integer")
     */
    private $month;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $totalViews;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $totalByDisctinctIp;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?Category
    {
        return $this->Category;
    }

    public function setCategory(?Category $Category): self
    {
        $this->Category = $Category;

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

    public function setTotalViews(?int $totalViews): self
    {
        $this->totalViews = $totalViews;

        return $this;
    }

    public function getTotalByDisctinctIp(): ?int
    {
        return $this->totalByDisctinctIp;
    }

    public function setTotalByDisctinctIp(?int $totalByDisctinctIp): self
    {
        $this->totalByDisctinctIp = $totalByDisctinctIp;

        return $this;
    }
}
