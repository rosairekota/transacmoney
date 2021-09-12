<?php

namespace App\Controller;

use App\Entity\Agence;
use App\Form\AgenceType;
use App\Repository\AgenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/agence")
 */
class AgenceController extends AbstractController
{

    /**
     * @Route("/", name="agence_index", methods={"GET"})
     * @IsGranted("ROLE_ADMINISTRATOR")
     */
    public function index(AgenceRepository $agenceRepository): Response
    {
        return $this->render('admin/agence/index.html.twig', [
            'agences'   => $agenceRepository->findAll()
        ]);
    }

    /**
     * @Route("/new", name="agence_new", methods={"GET","POST"})
     * @IsGranted("ROLE_WRITER")
     */
    public function new(Request $request): Response
    {
        $agence = new Agence();
        $form = $this->createForm(AgenceType::class, $agence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($agence);
            $entityManager->flush();

            return $this->redirectToRoute('agence_index');
        }

        return $this->render('admin/agence/new.html.twig', [
            'agence' => $agence,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="agence_show", methods={"GET"})
     * @IsGranted("ROLE_WRITER")
     */
    public function show(Agence $agence): Response
    {
        return $this->render('admin/agence/show.html.twig', [
            'agence' => $agence,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="agence_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_WRITER")
     */
    public function edit(Request $request, Agence $agence): Response
    {
        $form = $this->createForm(AgenceType::class, $agence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('agence_index');
        }

        return $this->render('admin/agence/edit.html.twig', [
            'agence' => $agence,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="agence_delete", methods={"DELETE"})
     * @IsGranted("ROLE_WRITER")
     */
    public function delete(Request $request, Agence $agence): Response
    {
        if ($this->isCsrfTokenValid('delete' . $agence->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($agence);
            $entityManager->flush();
        }

        return $this->redirectToRoute('agence_index');
    }
}
