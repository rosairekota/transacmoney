<?php

namespace App\Controller;

use App\Entity\Depot;
use App\Entity\Compte;
use App\Form\DepotType;
use App\Repository\UserRepository;
use App\Repository\DepotRepository;
use App\Repository\CompteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
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
    public function new($user_email, Request $request, UserRepository $userRepo, CompteRepository $accountUserRepot): Response
    {

        $user = $userRepo->findOneByUsernameOrEmail($user_email);
        $userAdmin = $userRepo->findOneByUsernameOrEmail("admin@transacmoney.com");
        $montantFinalCommission = 0;

        $depot = new Depot();
        $form = $this->createForm(DepotType::class, $depot);
        $form->handleRequest($request);
        $accountUser = $accountUserRepot->findByUser($user);
        $accountAdmin = $accountUserRepot->findByUser($userAdmin);
        $userByAccountType = $accountUserRepot->findByAccountTypeNumber(['id' => $user->getId(), 'type_compte' => 1]);


        if ($form->isSubmitted() && $form->isValid()) {
            dd($depot);

            if ($userByAccountType->solde < 0 || $userByAccountType->solde < $depot->getMontant()) {
                if ($userByAccountType->solde < 0) {
                    $this->addFlash("danger", "Dépot non autorisé. Votre caisse est epuisé. Veuillez contacter l'administrateur.");
                } elseif ($userByAccountType->solde < $depot->getMontant()) {
                    $this->addFlash("danger", "Dépot non autorisé. Votre solde est inferieur au montant du dépot. Veuillez contacter l'administrateur.");
                }
            } else {

                dd($userByAccountType->solde);

                $montantCommission = $depot->getMontant() * 0.025;

                $montantReel = $depot->getMontant() - floatval($montantCommission);

                $depot->setMontant($montantReel > 100 ? round($montantReel) : $montantReel);
                $codeSecret = str_shuffle($depot->getExpediteur()->getTelephone());
                $entityManager = $this->getDoctrine()->getManager();
                $depot->setCodeDepot($codeSecret);
                $depot->setUser_depot($user);

                $credit = $accountUser[0]->getMontantDebit() - $depot->getMontant();

                $accountUser[0]->setMontantCredit($depot->getMontant() + $accountUser[0]->getMontantCredit());
                $accountUser[0]->setMontantDebit(floor($credit));
                $solde = (floatval($accountUser[0]->getMontantDebit() - $accountUser[0]->getMontantCredit()));

                $accountUser[0]->setSolde($solde <= 0 ? 0 : $solde);

                // recherche role user
                if ($user->getRoles()[0] == 'ROLE_WRITER') {
                    $salaireSurCommission = round($montantCommission - 0.5, 3);

                    $montantFinalCommission = $montantCommission - floor($salaireSurCommission);
                    $depot->setMontantCommission($montantFinalCommission);

                    $accountUser[0]->setCommissionSousAgent($salaireSurCommission + $accountUser[0]->getCommissionSousAgent());


                    //$entityManager->persist($depot);
                }
                if ($userAdmin->getRoles()[0] == 'ROLE_SUPERUSER') {
                    $accountAdmin[0]->setCommissionSousAgent($montantFinalCommission + $accountAdmin[0]->getCommissionSousAgent());
                }

                $depot->setMontantCommission($montantCommission);

                $entityManager->persist($depot);


                $entityManager->flush();
                $this->addFlash('success', 'Le Dépot a été effectué avce succès');

                return $this->redirectToRoute('depot_index', ['user_email' => $user_email]);
            }
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
