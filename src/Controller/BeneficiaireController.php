<?php

namespace App\Controller;

use App\Entity\Beneficiaire;
use App\Form\BeneficiaireType;
use App\Repository\BeneficiaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/beneficiaire")
 */
class BeneficiaireController extends AbstractController
{
    /**
     * @Route("/", name="beneficiaire_index", methods={"GET"})
     * @IsGranted("ROLE_WRITER")
     */
    public function index(BeneficiaireRepository $beneficiaireRepository): Response
    {
        return $this->render('admin/beneficiaire/index.html.twig', [
            'beneficiaires' => $beneficiaireRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="beneficiaire_new", methods={"GET","POST"})
     * @IsGranted("ROLE_WRITER")
     */
    public function new(Request $request): Response
    {
        $beneficiaire = new Beneficiaire();
        $form = $this->createForm(BeneficiaireType::class, $beneficiaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($beneficiaire);
            $entityManager->flush();

            return $this->redirectToRoute('beneficiaire_index');
        }

        return $this->render('admin/beneficiaire/new.html.twig', [
            'beneficiaire' => $beneficiaire,
            'form' => $form->createView(),
        ]);
    }
     /**
     * @Route("/new/{user_email}-@j9a8j7k94", name="beneficiaire_depot", methods={"GET","POST"})
     * @IsGranted("ROLE_WRITER")
     */
    public function newDepot(Request $request,$user_email): Response
    {
        $beneficiaire = new Beneficiaire();
        $form = $this->createForm(BeneficiaireType::class, $beneficiaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($beneficiaire);
            $entityManager->flush();

            return $this->redirectToRoute('depot_new',["user_email"=>$user_email]);
        }

        return $this->render('admin/beneficiaire/new.html.twig', [
            'beneficiaire' => $beneficiaire,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}", name="beneficiaire_show", methods={"GET"})
     * @IsGranted("ROLE_ADMINISTRATOR")
     */
    public function show(Beneficiaire $beneficiaire): Response
    {
        return $this->render('admin/beneficiaire/show.html.twig', [
            'beneficiaire' => $beneficiaire,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="beneficiaire_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_WRITER")
     */
    public function edit(Request $request, Beneficiaire $beneficiaire): Response
    {
        $form = $this->createForm(BeneficiaireType::class, $beneficiaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('beneficiaire_index');
        }

        return $this->render('admin/beneficiaire/edit.html.twig', [
            'beneficiaire' => $beneficiaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="beneficiaire_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMINISTRATOR")
     */
    public function delete(Request $request, Beneficiaire $beneficiaire): Response
    {
        if ($this->isCsrfTokenValid('delete'.$beneficiaire->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($beneficiaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('beneficiaire_index');
    }
}
