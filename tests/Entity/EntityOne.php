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
class EntityOne
{
    /**
     * @Id()
     * @GeneratedValue
     * @Column(type="integer")
     */
    private $id;

    /**
     * @Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @Column(type="boolean")
     */
    private $active;

    /**
     * @Column(type="integer", nullable=true)
     */
    private $numberOfThings;

    /**
     * @OneToOne(targetEntity="BenRowan\DoctrineAssert\Tests\Entity\RootEntity", mappedBy="one", cascade={"persist", "remove"})
     */
    private $rootEntity;

    /**
     * @OneToOne(targetEntity="EntityOneOne", inversedBy="entityOne", cascade={"persist", "remove"})
     * @JoinColumn(nullable=false)
     */
    private $oneOne;

    /**
     * @OneToMany(targetEntity="EntityOneTwo", mappedBy="entityOne")
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

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getNumberOfThings(): ?int
    {
        return $this->numberOfThings;
    }

    public function setNumberOfThings(?int $numberOfThings): self
    {
        $this->numberOfThings = $numberOfThings;

        return $this;
    }

    public function getRootEntity(): ?RootEntity
    {
        return $this->rootEntity;
    }

    public function setRootEntity(RootEntity $rootEntity): self
    {
        $this->rootEntity = $rootEntity;

        // set the owning side of the relation if necessary
        if ($this !== $rootEntity->getOne()) {
            $rootEntity->setOne($this);
        }

        return $this;
    }

    public function getOneOne(): ?EntityOneOne
    {
        return $this->oneOne;
    }

    public function setOneOne(EntityOneOne $oneOne): self
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

    public function addOneTwo(EntityOneTwo $oneTwo): self
    {
        if (!$this->oneTwo->contains($oneTwo)) {
            $this->oneTwo[] = $oneTwo;
            $oneTwo->setEntityOne($this);
        }

        return $this;
    }

    public function removeOneTwo(EntityOneTwo $oneTwo): self
    {
        if ($this->oneTwo->contains($oneTwo)) {
            $this->oneTwo->removeElement($oneTwo);
            // set the owning side to null (unless already changed)
            if ($oneTwo->getEntityOne() === $this) {
                $oneTwo->setEntityOne(null);
            }
        }

        return $this;
    }
}
