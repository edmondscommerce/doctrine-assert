<?php

namespace BenRowan\DoctrineAssert\Tests\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * @Entity
 */
class EntityOneOne
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
     * @Column(type="boolean")
     */
    private $active;

    /**
     * @Column(type="integer", nullable=true)
     */
    private $numberOfThings;

    /**
     * @OneToOne(targetEntity="EntityOne", mappedBy="oneOne", cascade={"persist", "remove"})
     */
    private $entityOne;

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

    public function getEntityOne(): ?EntityOne
    {
        return $this->entityOne;
    }

    public function setEntityOne(EntityOne $entityOne): self
    {
        $this->entityOne = $entityOne;

        // set the owning side of the relation if necessary
        if ($this !== $entityOne->getOneOne()) {
            $entityOne->setOneOne($this);
        }

        return $this;
    }
}
