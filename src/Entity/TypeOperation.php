<?php

namespace App\Entity;

use App\Repository\TypeOperationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeOperationRepository::class)
 */
class TypeOperation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Operation::class, mappedBy="typeOperation")
     */
    private $operation_id;

    public function __construct()
    {
        $this->operation_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|Operation[]
     */
    public function getOperationId(): Collection
    {
        return $this->operation_id;
    }

    public function addOperationId(Operation $operationId): self
    {
        if (!$this->operation_id->contains($operationId)) {
            $this->operation_id[] = $operationId;
            $operationId->setTypeOperation($this);
        }

        return $this;
    }

    public function removeOperationId(Operation $operationId): self
    {
        if ($this->operation_id->removeElement($operationId)) {
            // set the owning side to null (unless already changed)
            if ($operationId->getTypeOperation() === $this) {
                $operationId->setTypeOperation(null);
            }
        }

        return $this;
    }
}
