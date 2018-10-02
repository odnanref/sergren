<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryViewRepository")
 * @ORM\HasLifecycleCallbacks
 */
class CategoryView
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
    private $ipaddress;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $useragent;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $referer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="categoryViews")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Category;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datecreated;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIpaddress(): ?string
    {
        return $this->ipaddress;
    }

    public function setIpaddress(string $ipaddress): self
    {
        $this->ipaddress = $ipaddress;

        return $this;
    }

    public function getUseragent(): ?string
    {
        return $this->useragent;
    }

    public function setUseragent(string $useragent): self
    {
        $this->useragent = $useragent;

        return $this;
    }

    public function getReferer(): ?string
    {
        return $this->referer;
    }

    public function setReferer(?string $referer): self
    {
        $this->referer = $referer;

        return $this;
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

    public function getDatecreated(): ?\DateTimeInterface
    {
        return $this->datecreated;
    }

    public function setDatecreated(\DateTimeInterface $datecreated): self
    {
        $this->datecreated = $datecreated;

        return $this;
    }
    
    /**
     *  @ORM\PrePersist
     */
    public function doStuffOnPrePersist()
    {
        $this->datecreated = new \DateTime("now");
    }
}
