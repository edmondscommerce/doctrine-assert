<?php

namespace BenRowan\DoctrineAssert\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class EntityOne
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
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numberOfThings;

    /**
     * @ORM\OneToOne(targetEntity="BenRowan\DoctrineAssert\Tests\Entity\RootEntity", mappedBy="one", cascade={"persist", "remove"})
     */
    private $rootEntity;

    /**
     * @ORM\OneToOne(targetEntity="EntityOneOne", inversedBy="entityOne", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $oneOne;

    /**
     * @ORM\OneToMany(targetEntity="EntityOneTwo", mappedBy="entityOne")
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
