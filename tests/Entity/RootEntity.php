<?php

namespace BenRowan\DoctrineAssert\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @Entity
 */
class RootEntity
{
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    private $id;

    /**
     * @Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @OneToOne(targetEntity="EntityOne", inversedBy="rootEntity", cascade={"persist", "remove"})
     * @JoinColumn(nullable=false)
     */
    private $one;

    /**
     * @OneToMany(targetEntity="BenRowan\DoctrineAssert\Tests\Entity\EntityTwo", mappedBy="rootEntity")
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
