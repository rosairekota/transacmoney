<?php

namespace App\Controller;

use App\Entity\Retrait;
use App\Form\RetraitType;
use App\Repository\RetraitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/retrait")
 */
class RetraitController extends AbstractController
{
    /**
     * @Route("/", name="retrait_index", methods={"GET"})
     */
    public function index(RetraitRepository $retraitRepository): Response
    {
        return $this->render('retrait/index.html.twig', [
            'retraits' => $retraitRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="retrait_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $retrait = new Retrait();
        $form = $this->createForm(RetraitType::class, $retrait);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($retrait);
            $entityManager->flush();

            return $this->redirectToRoute('retrait_index');
        }

        return $this->render('retrait/new.html.twig', [
            'retrait' => $retrait,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="retrait_show", methods={"GET"})
     */
    public function show(Retrait $retrait): Response
    {
        return $this->render('retrait/show.html.twig', [
            'retrait' => $retrait,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="retrait_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Retrait $retrait): Response
    {
        $form = $this->createForm(RetraitType::class, $retrait);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('retrait_index');
        }

        return $this->render('retrait/edit.html.twig', [
            'retrait' => $retrait,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="retrait_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Retrait $retrait): Response
    {
        if ($this->isCsrfTokenValid('delete'.$retrait->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($retrait);
            $entityManager->flush();
        }

        return $this->redirectToRoute('retrait_index');
    }
}
