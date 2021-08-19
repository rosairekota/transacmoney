<?php

namespace App\Controller;

use App\Entity\Credit;
use App\Form\CreditType;
use App\Repository\CreditRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/rechargement")
 * @IsGranted("ROLE_WRITER")
 */
class CreditController extends AbstractController
{
    /**
     * @Route("/", name="credit_index", methods={"GET"})
     */
    public function index(CreditRepository $creditRepository): Response
    {
        return $this->render('admin/credit/index.html.twig', [
            'credits' => $creditRepository->findAll(),
        ]);
    }

    /**
     * @Route("/fond-agence", name="credit_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $credit = new Credit();
        $form = $this->createForm(CreditType::class, $credit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $credit->setCreditCode(substr(str_shuffle(\sha1(str_repeat($credit->getAccount()->getId(), 5))), 1, 15));
            $account = $credit->getAccount();

            $credit->setHoldSolde($account->getSolde());
            $acountNewSold = $credit->getCreditAmount() + $credit->getAccount()->getSolde();
            $account->setSolde($acountNewSold);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($credit);
            $entityManager->persist($account);
            $entityManager->flush();

            return $this->redirectToRoute('credit_index');
        }

        return $this->render('admin/credit/new.html.twig', [
            'credit' => $credit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/fond-agence-{id}", name="credit_show", methods={"GET"})
     */
    public function show(Credit $credit): Response
    {
        return $this->render('admin/credit/show.html.twig', [
            'credit' => $credit,
        ]);
    }

    /**
     * @Route("/fond-agence-{id}/editer", name="credit_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Credit $credit, CreditRepository $creditRepo): Response
    {
        $form = $this->createForm(CreditType::class, $credit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //     $account = $credit->getAccount();
            //     $acountNewSold = 0;
            //     $creditHold = $creditRepo->findById($credit->getId());
            //     if ($creditHold->getCreditAmount() > $credit->getCreditAmount()) {
            //         $newAmount = $creditHold->getCreditAmount() - $credit->getCreditAmount();
            //         $credit->setCreditAmount($newAmount);
            //         dd($newAmount);
            //         $account->setSolde($credit->getAccount()->getSolde() - $newAmount);
            //     } else if ($creditHold->getCreditAmount() == $credit->getCreditAmount()) {

            //         $credit->setCreditAmount($credit->getCreditAmount());
            //         dd("naza awa2");
            //     } else {
            //         $acountNewSold = $credit->getAccount()->getSolde() + $credit->getCreditAmount();
            //         dd("ici 3");
            //     }

            //     //$credit->setHoldSolde($account->getSolde());
            // $entityManager = $this->getDoctrine()->getManager();
            //     $entityManager->persist($credit);
            //     $entityManager->persist($account);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('credit_index');
        }

        return $this->render('admin/credit/edit.html.twig', [
            'credit' => $credit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/fond-agence-supprimer/{id}", name="credit_delete")
     */
    public function delete(Credit $credit): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($credit);
        $entityManager->flush();

        return $this->json(["message" => "success", "value" => true]);
    }
}
