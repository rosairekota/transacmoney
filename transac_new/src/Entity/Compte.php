<?php

namespace App\Entity;

use App\Repository\CompteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CompteRepository::class)
 */
class Compte
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0, nullable=true)
     */
    private $montant_credit;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0, nullable=true)
     */
    private $montant_debit;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0, nullable=true)
     */
    private $solde;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comptes")
     */
    private $user_compte;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontantCredit(): ?string
    {
        return $this->montant_credit;
    }

    public function setMontantCredit(?string $montant_credit): self
    {
        $this->montant_credit = $montant_credit;

        return $this;
    }

    public function getMontantDebit(): ?string
    {
        return $this->montant_debit;
    }

    public function setMontantDebit(?string $montant_debit): self
    {
        $this->montant_debit = $montant_debit;

        return $this;
    }

    public function getSolde(): ?string
    {
        return $this->solde;
    }

    public function setSolde(?string $solde): self
    {
        $this->solde = $solde;

        return $this;
    }

    public function getUserCompte(): ?User
    {
        return $this->user_compte;
    }

    public function setUserCompte(?User $user_compte): self
    {
        $this->user_compte = $user_compte;

        return $this;
    }
}
