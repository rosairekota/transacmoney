<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\DebitRepository;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=DebitRepository::class)
 */
class Debit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     * @Assert\Positive(message="le Montant doit être positive !")
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $debit_code;

    /**
     * @ORM\Column(type="datetime")
     */
    private $request_date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $debit_date;

    /**
     * @ORM\ManyToOne(targetEntity=Compte::class, inversedBy="debits",cascade={"persist"})
     */
    private $account;

    /**
     * @var string
     * @Assert\NotNull(message="Pas de champ vide! inserer un numéro valide svp")
     */
    public $account_number;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="debits",cascade={"persist"})
     */
    private $user;

    public function __construct()
    {
        $this->request_date = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDebitCode(): ?string
    {
        return $this->debit_code;
    }

    public function setDebitCode(string $debit_code): self
    {
        $this->debit_code = $debit_code;

        return $this;
    }

    public function getRequestDate(): ?\DateTimeInterface
    {
        return $this->request_date;
    }

    public function setRequestDate(\DateTimeInterface $request_date): self
    {
        $this->request_date = $request_date;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDebitDate(): ?\DateTimeInterface
    {
        return $this->debit_date;
    }

    public function setDebitDate(?\DateTimeInterface $debit_date): self
    {
        $this->debit_date = $debit_date;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the value of account_number
     *
     * @return  string
     */
    public function getAccount_number()
    {
        return $this->account_number;
    }

    /**
     * Set the value of account_number
     *
     * @param  string  $account_number
     *
     * @return  self
     */
    public function setAccount_number(string $account_number)
    {
        $this->account_number = $account_number;

        return $this;
    }
}
