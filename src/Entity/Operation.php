<?php

namespace App\Entity;

use App\Repository\OperationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OperationRepository::class)
 */
class Operation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_operation;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $code_operation;


    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $montant_debit;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $montant_credit;

    /**
     * @ORM\ManyToOne(targetEntity=Compte::class, inversedBy="operations")
     */
    private $compte;

    /**
     * @ORM\ManyToOne(targetEntity=TypeOperation::class, inversedBy="operation_id")
     */
    private $typeOperation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateOperation(): ?\DateTimeInterface
    {
        return $this->date_operation;
    }

    public function setDateOperation(\DateTimeInterface $date_operation): self
    {
        $this->date_operation = $date_operation;

        return $this;
    }

    public function getMontantDebit(): ?float
    {
        return $this->montant_debit;
    }

    public function setMontantDebit(?float $montant_debit): self
    {
        $this->montant_debit = $montant_debit;

        return $this;
    }

    public function getMontantCredit(): ?float
    {
        return $this->montant_credit;
    }

    public function setMontantCredit(?float $montant_credit): self
    {
        $this->montant_credit = $montant_credit;

        return $this;
    }

    public function getCompteId(): ?Compte
    {
        return $this->compte;
    }

    public function setCompteId(?Compte $compte): self
    {
        $this->compte = $compte;

        return $this;
    }

    public function getTypeOperation(): ?TypeOperation
    {
        return $this->typeOperation;
    }

    public function setTypeOperation(?TypeOperation $typeOperation): self
    {
        $this->typeOperation = $typeOperation;

        return $this;
    }

    /**
     * Get the value of codeOperation
     */
    public function getCodeOperation(): ?string
    {
        return $this->code_operation;
    }

    /**
     * Set the value of codeOperation
     *
     * @return  self
     */
    public function setCodeOperation(string $code_operation): self
    {
        $this->code_operation = $code_operation;

        return $this;
    }
}
