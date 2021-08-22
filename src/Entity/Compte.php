<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CompteRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

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

    /**
     * @ORM\OneToMany(targetEntity=Credit::class, mappedBy="account")
     */
    private $credits;

    /**
     * @ORM\OneToMany(targetEntity=Debit::class, mappedBy="account")
     */
    private $debits;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     */
    private $user;


    public function __construct()
    {
        $this->date_ouverture = new \DateTime();
        $this->numero_compte = str_shuffle("1234");
        $this->credits = new ArrayCollection();
        $this->debits = new ArrayCollection();
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

    /**
     * @return Collection|Credit[]
     */
    public function getCredits(): Collection
    {
        return $this->credits;
    }

    public function addCredit(Credit $credit): self
    {
        if (!$this->credits->contains($credit)) {
            $this->credits[] = $credit;
            $credit->setAccount($this);
        }

        return $this;
    }

    public function removeCredit(Credit $credit): self
    {
        if ($this->credits->removeElement($credit)) {
            // set the owning side to null (unless already changed)
            if ($credit->getAccount() === $this) {
                $credit->setAccount(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Debit[]
     */
    public function getDebits(): Collection
    {
        return $this->debits;
    }

    public function addDebit(Debit $debit): self
    {
        if (!$this->debits->contains($debit)) {
            $this->debits[] = $debit;
            $debit->setAccount($this);
        }

        return $this;
    }

    public function removeDebit(Debit $debit): self
    {
        if ($this->debits->removeElement($debit)) {
            // set the owning side to null (unless already changed)
            if ($debit->getAccount() === $this) {
                $debit->setAccount(null);
            }
        }

        return $this;
    }

    /**
     * Get the value of user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @return  self
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }
}
