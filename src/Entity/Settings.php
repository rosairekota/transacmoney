<?php

namespace App\Entity;

use App\Repository\SettingsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SettingsRepository::class)
 */
class Settings
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $minAmount;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $costSend;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $sendCommission;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $retreatProfit;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $sendProfit;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMinAmount(): ?float
    {
        return $this->minAmount;
    }

    public function setMinAmount(float $minAmount): self
    {
        $this->minAmount = $minAmount;

        return $this;
    }

    public function getCostSend(): ?float
    {
        return $this->costSend;
    }

    public function setCostSend(float $costSend): self
    {
        $this->costSend = $costSend;

        return $this;
    }

    public function getSendCommission(): ?float
    {
        return $this->sendCommission;
    }

    public function setSendCommission(?float $sendCommission): self
    {
        $this->sendCommission = $sendCommission;

        return $this;
    }

    public function getRetreatProfit(): ?float
    {
        return $this->retreatProfit;
    }

    public function setRetreatProfit(?float $retreatProfit): self
    {
        $this->retreatProfit = $retreatProfit;

        return $this;
    }

    public function getSendProfit(): ?float
    {
        return $this->sendProfit;
    }

    public function setSendProfit(?float $sendProfit): self
    {
        $this->sendProfit = $sendProfit;

        return $this;
    }
}
