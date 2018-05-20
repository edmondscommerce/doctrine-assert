<?php

namespace BenRowan\DoctrineAssert\Tests\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class EntityOneOne
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
     * @ORM\OneToOne(targetEntity="EntityOne", mappedBy="oneOneOne", cascade={"persist", "remove"})
     */
    private $entityOneOne;

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

    public function getEntityOneOne(): ?EntityOne
    {
        return $this->entityOneOne;
    }

    public function setEntityOneOne(EntityOne $entityOneOne): self
    {
        $this->entityOneOne = $entityOneOne;

        // set the owning side of the relation if necessary
        if ($this !== $entityOneOne->getOneOneOne()) {
            $entityOneOne->setOneOneOne($this);
        }

        return $this;
    }
}
