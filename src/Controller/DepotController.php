<?php

namespace App\Controller;

use App\Entity\Compte;
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
    private $user_email;

    /**
     * @Route("/{user_email}-@j9a8j7k94", name="depot_index", methods={"GET"})
     * @IsGranted("ROLE_WRITER")
     */
    public function index(DepotRepository $depotRepository, $user_email, UserRepository $userRepo): Response
    {
        $this->user_email = $user_email;
        if ($user_email == 'admin@transacmoney.com') {
            $depots = $depotRepository->findAll();
        } else {
            $user = $userRepo->findOneByUsernameOrEmail($user_email);
            $depots = $depotRepository->findByEmail($user->getId());
            //$depots=$depotRepository->selectByIdSql(['id'=>$user->getId()]);


        }
        return $this->render('admin/depot/index.html.twig', [
            'depots' =>  $depots,
        ]);
    }

    /**
     * @Route("/new/{user_email}-@j9a8j7k94", name="depot_new", methods={"GET","POST"})
     * @IsGranted("ROLE_WRITER")
     */
    public function new($user_email, Request $request, UserRepository $userRepo): Response
    {
        $account = new Compte();
        $user = $userRepo->findOneByUsernameOrEmail($user_email);

        $depot = new Depot();
        $form = $this->createForm(DepotType::class, $depot);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            dd($depot);
            $montantCommission = $depot->getMontant() * 0.02;
            $montantReel = intval($depot->getMontant()) - $montantCommission;

            $depot->setMontant(round($montantReel));
            $codeSecret = str_shuffle($depot->getExpediteur()->getTelephone());
            $entityManager = $this->getDoctrine()->getManager();
            $depot->setCodeDepot($codeSecret);
            $depot->setUser_depot($user);

            // recherche role user
            if ($user->getRoles()[0] == 'ROLE_WRITER') {
                $salaireSurCommission = $montantCommission * 0.005;

                $montantCommissionApres = floor($montantCommission) - $salaireSurCommission;
                $depot->setMontantCommission($montantCommissionApres);

                $account->setMontantCredit(0);
                $account->setMontantDebit($salaireSurCommission);

                $account->setSolde(floatval($account->getMontantDebit() - $account->getMontantCredit()));
                $account->setUserCompte($user);

                $entityManager->persist($account);
                $entityManager->persist($depot);
            } else {
                $depot->setMontantCommission(floor($montantCommission));
                $entityManager->persist($depot);
            }


            $entityManager->flush();
            $this->addFlash('success', 'Le Dépot a été effectué avce succès');

            return $this->redirectToRoute('depot_index', ['user_email' => $user_email]);
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
     * @Route("/{id}/edit/{user_email}-@j9a8j7k94", name="depot_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_WRITER")
     */
    public function edit(Request $request, Depot $depot, $user_email): Response
    {
        $form = $this->createForm(DepotType::class, $depot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('depot_index', ['user_email' => $user_email]);
        }

        return $this->render('admin/depot/edit.html.twig', [
            'depot' => $depot,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{user_email}-@j9a8j7k94", name="depot_delete", methods={"DELETE"})
     * @IsGranted("ROLE_WRITER")
     */
    public function delete(Request $request, Depot $depot, $user_email): Response
    {
        if ($this->isCsrfTokenValid('delete' . $depot->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($depot);
            $entityManager->flush();
        }

        return $this->redirectToRoute('depot_index', ['user_email' => $user_email]);
    }
}
