<?php

namespace App\Services;

use App\Entity\Credit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CreditService extends AbstractController
{
    public function create(Credit $credit)
    {
        $credit->setCreditCode(substr(str_shuffle(\sha1(str_repeat($credit->getAccount()->getId(), 5))), 1, 15));
        $account = $credit->getAccount();

        $credit->setHoldSolde($account->getSolde());
        $acountNewSold = $credit->getCreditAmount() + $credit->getAccount()->getSolde();
        $account->setSolde($acountNewSold);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($credit);
        $entityManager->persist($account);
        $entityManager->flush();
    }
}
