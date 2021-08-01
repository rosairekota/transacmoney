<?php

namespace App\Entity;

use App\Repository\CompteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /** @ORM\Column(type="string", length=50, nullable=true)
     */
    private $numero_compte;


    /**
     * @ORM\Column(type="datetime")
     */
    private $date_ouverture;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fin_validite;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $solde;

    public function __construct()
    {
        $this->date_ouverture = new \DateTime();
        $this->numero_compte = str_shuffle("1234");
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroCompte(): ?string
    {
        return $this->numero_compte;
    }

    public function setNumeroCompte(?string $numero_compte): self
    {
        $this->numero_compte = $numero_compte;

        return $this;
    }



    public function getDateOuverture(): ?\DateTimeInterface
    {
        return $this->date_ouverture;
    }

    public function setDateOuverture(\DateTimeInterface $date_ouverture): self
    {
        $this->date_ouverture = $date_ouverture;

        return $this;
    }

    public function getFinValidite(): ?\DateTimeInterface
    {
        return $this->fin_validite;
    }

    public function setFinValidite(?\DateTimeInterface $fin_validite): self
    {
        $this->fin_validite = $fin_validite;

        return $this;
    }

    public function getSolde(): ?float
    {
        return $this->solde;
    }

    public function setSolde(?float $solde): self
    {
        $this->solde = $solde;

        return $this;
    }
}
