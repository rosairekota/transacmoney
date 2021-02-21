<?php

namespace App\Entity;

use App\Repository\DepotRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DepotRepository::class)
 */
class Depot
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $montant;

    /**
     * @ORM\ManyToOne(targetEntity=Expediteur::class, inversedBy="depots")
     */
    private $expediteur;

    /**
     * @ORM\ManyToOne(targetEntity=Beneficiaire::class, inversedBy="depots")
     */
    private $beneficiaire;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $codeDepot;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="depots")
     */
    private $user_depot;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_depot;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="depots")
     */
    private $ville;

    /**
     * @ORM\Column(type="float")
     */
    private $montantCommission;

    /**
     * @ORM\OneToMany(targetEntity=Retrait::class, mappedBy="depot")
     */
    private $retraits;

    public function __construct()
    {
        $this->retraits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function setMontant(string $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getExpediteur(): ?Expediteur
    {
        return $this->expediteur;
    }

    public function setExpediteur(?Expediteur $expediteur): self
    {
        $this->expediteur = $expediteur;

        return $this;
    }

    public function getBeneficiaire(): ?Beneficiaire
    {
        return $this->beneficiaire;
    }

    public function setBeneficiaire(?Beneficiaire $beneficiaire): self
    {
        $this->beneficiaire = $beneficiaire;

        return $this;
    }

    public function getCodeDepot(): ?string
    {
        return $this->codeDepot;
    }

    public function setCodeDepot(string $codeDepot): self
    {
        $this->codeDepot = $codeDepot;

        return $this;
    }



    public function getDateDepot(): ?\DateTimeInterface
    {
        return $this->date_depot;
    }

    public function setDateDepot(\DateTimeInterface $date_depot): self
    {
        $this->date_depot = $date_depot;

        return $this;
    }

    public function getVille(): ?City
    {
        return $this->ville;
    }

    public function setVille(?City $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getMontantCommission(): ?float
    {
        return $this->montantCommission;
    }

    public function setMontantCommission(float $montantCommission): self
    {
        $this->montantCommission = $montantCommission;

        return $this;
    }

    /**
     * @return Collection|Retrait[]
     */
    public function getRetraits(): Collection
    {
        return $this->retraits;
    }

    public function addRetrait(Retrait $retrait): self
    {
        if (!$this->retraits->contains($retrait)) {
            $this->retraits[] = $retrait;
            $retrait->setDepot($this);
        }

        return $this;
    }

    public function removeRetrait(Retrait $retrait): self
    {
        if ($this->retraits->removeElement($retrait)) {
            // set the owning side to null (unless already changed)
            if ($retrait->getDepot() === $this) {
                $retrait->setDepot(null);
            }
        }

        return $this;
    }

    /**
     * Get the value of user_depot
     */ 
    public function getUser_depot()
    {
        return $this->user_depot;
    }

    /**
     * Set the value of user_depot
     *
     * @return  self
     */ 
    public function setUser_depot($user_depot)
    {
        $this->user_depot = $user_depot;

        return $this;
    }
}
