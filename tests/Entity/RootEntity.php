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
    private $one;

    /**
     * @ORM\OneToMany(targetEntity="BenRowan\DoctrineAssert\Tests\Entity\EntityTwo", mappedBy="rootEntity")
     */
    private $two;

    public function __construct()
    {
        $this->two = new ArrayCollection();
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

    public function getOne(): ?EntityOne
    {
        return $this->one;
    }

    public function setOne(EntityOne $one): self
    {
        $this->one = $one;

        return $this;
    }

    /**
     * @return Collection|EntityTwo[]
     */
    public function getTwo(): Collection
    {
        return $this->two;
    }

    public function addTwo(EntityTwo $two): self
    {
        if (!$this->two->contains($two)) {
            $this->two[] = $two;
            $two->setRootEntity($this);
        }

        return $this;
    }

    public function removeTwo(EntityTwo $two): self
    {
        if ($this->two->contains($two)) {
            $this->two->removeElement($two);
            // set the owning side to null (unless already changed)
            if ($two->getRootEntity() === $this) {
                $two->setRootEntity(null);
            }
        }

        return $this;
    }
}
