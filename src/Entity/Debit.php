<?php

namespace App\Entity;

use App\Repository\DebitRepository;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\ManyToOne(targetEntity=Compte::class, inversedBy="debits")
     */
    private $account;

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
}
