<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SellingContactRepository")
 */
class SellingContact
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
     * @ORM\Column(type="string", length=20)
     */
    private $phone1;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $phone2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $bestcontacttime;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $brand;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $model;

    /**
     * @ORM\Column(type="integer")
     */
    private $yearmade;

    /**
     * @ORM\Column(type="integer")
     */
    private $yearmodel;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fuel;

    /**
     * @ORM\Column(type="integer")
     */
    private $millage;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $collor;

    /**
     * @ORM\Column(type="integer")
     */
    private $doors;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gears;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $state_chassi;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $engine;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $glass;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $exhaust;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tires;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $transmission;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $interior;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $hasdamage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

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

    public function getPhone1(): ?string
    {
        return $this->phone1;
    }

    public function setPhone1(string $phone1): self
    {
        $this->phone1 = $phone1;

        return $this;
    }

    public function getPhone2(): ?string
    {
        return $this->phone2;
    }

    public function setPhone2(?string $phone2): self
    {
        $this->phone2 = $phone2;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getBestcontacttime(): ?string
    {
        return $this->bestcontacttime;
    }

    public function setBestcontacttime(string $bestcontacttime): self
    {
        $this->bestcontacttime = $bestcontacttime;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getYearmade(): ?int
    {
        return $this->yearmade;
    }

    public function setYearmade(int $yearmade): self
    {
        $this->yearmade = $yearmade;

        return $this;
    }

    public function getYearmodel(): ?int
    {
        return $this->yearmodel;
    }

    public function setYearmodel(int $yearmodel): self
    {
        $this->yearmodel = $yearmodel;

        return $this;
    }

    public function getMillage(): ?int
    {
        return $this->millage;
    }

    public function setMillage(int $millage): self
    {
        $this->millage = $millage;

        return $this;
    }

    public function getCollor(): ?string
    {
        return $this->collor;
    }

    public function setCollor(string $collor): self
    {
        $this->collor = $collor;

        return $this;
    }

    public function getDoors(): ?int
    {
        return $this->doors;
    }

    public function setDoors(int $doors): self
    {
        $this->doors = $doors;

        return $this;
    }

    public function getGears(): ?string
    {
        return $this->gears;
    }

    public function setGears(string $gears): self
    {
        $this->gears = $gears;

        return $this;
    }

    public function getStateChassi(): ?int
    {
        return $this->state_chassi;
    }

    public function setStateChassi(?int $state_chassi): self
    {
        $this->state_chassi = $state_chassi;

        return $this;
    }

    public function getEngine(): ?int
    {
        return $this->engine;
    }

    public function setEngine(?int $engine): self
    {
        $this->engine = $engine;

        return $this;
    }

    public function getGlass(): ?int
    {
        return $this->glass;
    }

    public function setGlass(?int $glass): self
    {
        $this->glass = $glass;

        return $this;
    }

    public function getExhaust(): ?int
    {
        return $this->exhaust;
    }

    public function setExhaust(?int $exhaust): self
    {
        $this->exhaust = $exhaust;

        return $this;
    }

    public function getTires(): ?int
    {
        return $this->tires;
    }

    public function setTires(?int $tires): self
    {
        $this->tires = $tires;

        return $this;
    }

    public function getTransmission(): ?int
    {
        return $this->transmission;
    }

    public function setTransmission(?int $transmission): self
    {
        $this->transmission = $transmission;

        return $this;
    }

    public function getInterior(): ?int
    {
        return $this->interior;
    }

    public function setInterior(?int $interior): self
    {
        $this->interior = $interior;

        return $this;
    }

    public function getHasdamage(): ?bool
    {
        return $this->hasdamage;
    }

    public function setHasdamage(?bool $hasdamage): self
    {
        $this->hasdamage = $hasdamage;

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
    
    public function getFuel() : ?string {
        return $this->fuel;
    }
    
    public function setFuel(?string $fuel) : self {
        $this->fuel = $fuel;
        return $this;
    }
}
