<?php

namespace App\Controller;

use App\Entity\Debit;
use App\Form\DebitType;
use App\Repository\DebitRepository;
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
        return $this->render('debit/index.html.twig', [
            'debits' => $debitRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="debit_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $debit = new Debit();
        $form = $this->createForm(DebitType::class, $debit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($debit);
            $entityManager->flush();

            return $this->redirectToRoute('debit_index');
        }

        return $this->render('debit/new.html.twig', [
            'debit' => $debit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="debit_show", methods={"GET"})
     */
    public function show(Debit $debit): Response
    {
        return $this->render('debit/show.html.twig', [
            'debit' => $debit,
        ]);
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

        return $this->render('debit/edit.html.twig', [
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
