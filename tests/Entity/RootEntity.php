<?php

namespace BenRowan\DoctrineAssert\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class RootEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity="EntityOne", inversedBy="rootEntity", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $oneOne;

    /**
     * @ORM\OneToMany(targetEntity="BenRowan\DoctrineAssert\Tests\Entity\EntityOneTwo", mappedBy="rootEntity")
     */
    private $oneTwo;

    public function __construct()
    {
        $this->oneTwo = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getOneOne(): ?EntityOne
    {
        return $this->oneOne;
    }

    public function setOneOne(EntityOne $oneOne): self
    {
        $this->oneOne = $oneOne;

        return $this;
    }

    /**
     * @return Collection|EntityTwo[]
     */
    public function getOneTwo(): Collection
    {
        return $this->oneTwo;
    }

    public function addOneTwo(EntityTwo $oneTwo): self
    {
        if (!$this->oneTwo->contains($oneTwo)) {
            $this->oneTwo[] = $oneTwo;
            $oneTwo->setRootEntity($this);
        }

        return $this;
    }

    public function removeOneTwo(EntityTwo $oneTwo): self
    {
        if ($this->oneTwo->contains($oneTwo)) {
            $this->oneTwo->removeElement($oneTwo);
            // set the owning side to null (unless already changed)
            if ($oneTwo->getRootEntity() === $this) {
                $oneTwo->setRootEntity(null);
            }
        }

        return $this;
    }
}
