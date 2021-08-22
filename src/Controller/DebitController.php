<?php

namespace App\Controller;

use DateTime;
use App\Entity\Debit;
use App\Entity\Credit;
use App\Form\DebitType;
use App\Services\CreditService;
use App\Repository\DebitRepository;
use App\Controller\CreditController;
use App\Repository\CompteRepository;
use App\Repository\CreditRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/paiements")
 * @IsGranted("ROLE_WRITER")
 */
class DebitController extends AbstractController
{
    /**
     * @Route("/", name="debit_index", methods={"GET"})
     */
    public function index(DebitRepository $debitRepository): Response
    {
        return $this->render('admin/debit/index.html.twig', [
            'debits' => $debitRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="debit_new", methods={"GET","POST"})
     */
    public function new(Request $request, CompteRepository $compteRepository): Response
    {
        $debit = new Debit();
        $form = $this->createForm(DebitType::class, $debit);
        $form->handleRequest($request);
        $counter = 1;
        if ($form->isSubmitted() && $form->isValid()) {
            $accompteUserDebit = $compteRepository->findOneBy(['numero_compte' => $debit->getAccount_number()]);
            if (!empty($accompteUserDebit)) {

                if ($accompteUserDebit->getSolde() > floatval($debit->getAmount())) {
                    $debit->setAccount($accompteUserDebit);
                    $debit->setStatus(false);

                    $debit->setDebitCode(substr(str_shuffle(\md5(str_repeat($debit->getAccount_number(), 5))), 1, 10));
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($debit);
                    $entityManager->flush();
                    $this->addFlash('success', "Votre demande a été envoyée avec succèss");
                    return $this->redirectToRoute('debit_new');
                } else {
                    $this->addFlash('danger', "Veuillez inserer un montant inferieur ou egal à votre solde");
                }
            } else {
                $this->addFlash('danger', "le numero de ce compte n'existe pas");
            }
        }

        return $this->render('admin/debit/new.html.twig', [
            'debit' => $debit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/valider-{id}", name="debit_show", methods={"GET"})
     */
    public function show(Debit $debit, DebitRepository $debitRepository, CreditService $creditService, CompteRepository $compteRepository): Response
    {
        // initialisation
        $credit = new Credit();


        // on traite le paiment de l'agence demandeuse

        $accountWantedAgency = $debit->getAccount();
        $account = $compteRepository->findOneById($accountWantedAgency->getId());
        if ($debit->getAmount() < $account->getSolde()) {
            $account->setSolde($account->getSolde() - $debit->getAmount());
            $debit->setStatus(true);
            $debit->getDebitDate(new DateTime());
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($account);
            $entityManager->persist($debit);

            $credit->setCreditAmount(floatval($debit->getAmount()));
            $credit->setAccount($debit->getUser()->getAccount());
            $entityManager->flush();
            $creditService->create($credit);
            $this->addFlash('success', "Demande validée avec success!");
            return $this->redirectToRoute('debit_index');
        } else {
            # code...
        }

        return $this->json(["message" => "success", "value" => true]);
    }

    /**
     * @Route("/{id}/edit", name="debit_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Debit $debit): Response
    {
        $form = $this->createForm(DebitType::class, $debit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('debit_index');
        }

        return $this->render('admin/debit/edit.html.twig', [
            'debit' => $debit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="debit_delete", methods={"POST"})
     */
    public function delete(Request $request, Debit $debit): Response
    {
        if ($this->isCsrfTokenValid('delete' . $debit->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($debit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('debit_index');
    }
}
