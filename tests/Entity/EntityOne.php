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
     * @ORM\OneToOne(targetEntity="BenRowan\DoctrineAssert\Tests\Entity\RootEntity", mappedBy="oneOne", cascade={"persist", "remove"})
     */
    private $rootEntity;

    /**
     * @ORM\OneToOne(targetEntity="EntityOneOne", inversedBy="entityOneOne", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $oneOneOne;

    /**
     * @ORM\OneToMany(targetEntity="EntityOneTwo", mappedBy="entityOneOne")
     */
    private $oneOneTwo;

    public function __construct()
    {
        $this->oneOneTwo = new ArrayCollection();
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
        if ($this !== $rootEntity->getOneOne()) {
            $rootEntity->setOneOne($this);
        }

        return $this;
    }

    public function getOneOneOne(): ?EntityOneOne
    {
        return $this->oneOneOne;
    }

    public function setOneOneOne(EntityOneOne $oneOneOne): self
    {
        $this->oneOneOne = $oneOneOne;

        return $this;
    }

    /**
     * @return Collection|EntityTwo[]
     */
    public function getOneOneTwo(): Collection
    {
        return $this->oneOneTwo;
    }

    public function addOneOneTwo(EntityTwo $oneOneTwo): self
    {
        if (!$this->oneOneTwo->contains($oneOneTwo)) {
            $this->oneOneTwo[] = $oneOneTwo;
            $oneOneTwo->setEntityOneOne($this);
        }

        return $this;
    }

    public function removeOneOneTwo(EntityTwo $oneOneTwo): self
    {
        if ($this->oneOneTwo->contains($oneOneTwo)) {
            $this->oneOneTwo->removeElement($oneOneTwo);
            // set the owning side to null (unless already changed)
            if ($oneOneTwo->getEntityOneOne() === $this) {
                $oneOneTwo->setEntityOneOne(null);
            }
        }

        return $this;
    }
}
