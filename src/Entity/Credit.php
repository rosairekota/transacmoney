<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CreditRepository;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CreditRepository::class)
 */
class Credit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     *  @Assert\Positive(message="le Montant doit être positive")
     */
    private $credit_amount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $credit_code;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Compte::class, inversedBy="credits")
     */
    private $account;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $hold_solde;

    public function __construct()
    {
        $this->created_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreditAmount(): ?float
    {
        return $this->credit_amount;
    }

    public function setCreditAmount(float $credit_amount): self
    {
        $this->credit_amount = $credit_amount;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getCreditCode(): ?string
    {
        return $this->credit_code;
    }

    public function setCreditCode(string $credit_code): self
    {
        $this->credit_code = $credit_code;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAccount(): ?Compte
    {
        return $this->account;
    }

    public function setAccount(?Compte $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getHoldSolde(): ?float
    {
        return $this->hold_solde;
    }

    public function setHoldSolde(?float $hold_solde): self
    {
        $this->hold_solde = $hold_solde;

        return $this;
    }
}
