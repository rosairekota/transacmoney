<?php

namespace App\Controller;

use App\Entity\Depot;
use App\Form\DepotType;
use App\Repository\DepotRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/depot")
 */
class DepotController extends AbstractController
{
    /**
     * @Route("/", name="depot_index", methods={"GET"})
     * @IsGranted("ROLE_WRITER")
     */
    public function index(DepotRepository $depotRepository): Response
    {
        return $this->render('admin/depot/index.html.twig', [
            'depots' => $depotRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{user_email}", name="depot_new", methods={"GET","POST"})
     * @IsGranted("ROLE_WRITER")
     */
    public function new($user_email,Request $request,UserRepository $userRepo): Response
    {
       
        $user=$userRepo->findOneByUsernameOrEmail($user_email);
       
        $depot = new Depot();
        $form = $this->createForm(DepotType::class, $depot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $montantCommission=($depot->getMontant()*5)/100;
            $codeSecret=str_shuffle($depot->getExpediteur()->getTelephone());
            $depot->setMontantCommission($montantCommission);
            $depot->setCodeDepot($codeSecret);
            $depot->setUser_depot($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($depot);
            $entityManager->flush();
            $this->addFlash('success','Le Dépot a été effectué avce succès');

            return $this->redirectToRoute('depot_index');
        }

        return $this->render('admin/depot/new.html.twig', [
            'depot' => $depot,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="depot_show", methods={"GET"})
     * @IsGranted("ROLE_WRITER")
     */
    public function show(Depot $depot): Response
    {
        return $this->render('admin/depot/show.html.twig', [
            'depot' => $depot,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="depot_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_WRITER")
     */
    public function edit(Request $request, Depot $depot): Response
    {
        $form = $this->createForm(DepotType::class, $depot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('depot_index');
        }

        return $this->render('admin/depot/edit.html.twig', [
            'depot' => $depot,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="depot_delete", methods={"DELETE"})
     * @IsGranted("ROLE_WRITER")
     */
    public function delete(Request $request, Depot $depot): Response
    {
        if ($this->isCsrfTokenValid('delete'.$depot->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($depot);
            $entityManager->flush();
        }

        return $this->redirectToRoute('depot_index');
    }
}
