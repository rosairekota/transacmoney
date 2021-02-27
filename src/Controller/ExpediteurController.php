<?php

namespace App\Controller;

use App\Entity\Expediteur;
use App\Form\ExpediteurType;
use App\Repository\ExpediteurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/expediteur")
 */
class ExpediteurController extends AbstractController
{
    /**
     * @Route("/", name="expediteur_index", methods={"GET"})
     * @IsGranted("ROLE_WRITER")
     */
    public function index(ExpediteurRepository $expediteurRepository): Response
    {
        return $this->render('admin/expediteur/index.html.twig', [
            'expediteurs' => $expediteurRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="expediteur_new", methods={"GET","POST"})
     * @IsGranted("ROLE_WRITER")
     */
    public function new(Request $request): Response
    {
        $expediteur = new Expediteur();
        $form = $this->createForm(ExpediteurType::class, $expediteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($expediteur);
            $entityManager->flush();

            return $this->redirectToRoute('expediteur_index');
        }

        return $this->render('admin/expediteur/new.html.twig', [
            'expediteur' => $expediteur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="expediteur_show", methods={"GET"})
     * @IsGranted("ROLE_ADMINISTRATOR")
     */
    public function show(Expediteur $expediteur): Response
    {
        return $this->render('admin/expediteur/show.html.twig', [
            'expediteur' => $expediteur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="expediteur_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_WRITER")
     */
    public function edit(Request $request, Expediteur $expediteur): Response
    {
        $form = $this->createForm(ExpediteurType::class, $expediteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('expediteur_index');
        }

        return $this->render('admin/expediteur/edit.html.twig', [
            'expediteur' => $expediteur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="expediteur_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMINISTRATOR")
     */
    public function delete(Request $request, Expediteur $expediteur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$expediteur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($expediteur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('expediteur_index');
    }
}
