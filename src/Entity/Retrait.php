<?php

namespace App\Entity;

use App\Repository\RetraitRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RetraitRepository::class)
 */
class Retrait
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
    private $montantRetire;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0, nullable=true)
     */
    private $montantRestant;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_retrait;

    /**
     * @ORM\ManyToOne(targetEntity=Depot::class, inversedBy="retraits")
     */
    private $depot;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $beneficiaire_piece_type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $beneficiaire_piece_image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $beneficiaire_piece_numero;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="retraits")
     */
    private $user_retrait;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $libelle;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontantRetire(): ?string
    {
        return $this->montantRetire;
    }

    public function setMontantRetire(string $montantRetire): self
    {
        $this->montantRetire = $montantRetire;

        return $this;
    }

    public function getMontantRestant(): ?string
    {
        return $this->montantRestant;
    }

    public function setMontantRestant(?string $montantRestant): self
    {
        $this->montantRestant = $montantRestant;

        return $this;
    }

    public function getDateRetrait(): ?\DateTimeInterface
    {
        return $this->date_retrait;
    }

    public function setDateRetrait(\DateTimeInterface $date_retrait): self
    {
        $this->date_retrait = $date_retrait;

        return $this;
    }

    public function getDepot(): ?Depot
    {
        return $this->depot;
    }

    public function setDepot(?Depot $depot): self
    {
        $this->depot = $depot;

        return $this;
    }

    public function getBeneficiairePieceType(): ?string
    {
        return $this->beneficiaire_piece_type;
    }

    public function setBeneficiairePieceType(string $beneficiaire_piece_type): self
    {
        $this->beneficiaire_piece_type = $beneficiaire_piece_type;

        return $this;
    }

    public function getBeneficiairePieceImage(): ?string
    {
        return $this->beneficiaire_piece_image;
    }

    public function setBeneficiairePieceImage(string $beneficiaire_piece_image): self
    {
        $this->beneficiaire_piece_image = $beneficiaire_piece_image;

        return $this;
    }

    public function getBeneficiairePieceNumero(): ?string
    {
        return $this->beneficiaire_piece_numero;
    }

    public function setBeneficiairePieceNumero(string $beneficiaire_piece_numero): self
    {
        $this->beneficiaire_piece_numero = $beneficiaire_piece_numero;

        return $this;
    }

    public function getUserRetrait(): ?User
    {
        return $this->user_retrait;
    }

    public function setUserRetrait(?User $user_retrait): self
    {
        $this->user_retrait = $user_retrait;

        return $this;
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
}
