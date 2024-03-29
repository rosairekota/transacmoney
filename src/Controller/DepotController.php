<?php

namespace App\Controller;

use App\Entity\Depot;
use App\Entity\Compte;
use App\Form\DepotType;
use App\Repository\UserRepository;
use App\Services\ReportingService;
use App\Repository\DepotRepository;
use App\Repository\CompteRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Repository\RepositoryFactory;
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
     * @var UserRepository
     */
    private $userRepo;

    private DepotRepository $depotRepo;

    public function __construct(UserRepository $userRepo, DepotRepository $depotRepo)
    {
        $this->userRepo = $userRepo;
        $this->depotRepo = $depotRepo;
    }

    /**
     * @Route("/les-depots", name="depot_index", methods={"GET"})
     * @IsGranted("ROLE_WRITER")
     */
    public function index(): Response
    {
        $currentUser = unserialize($_SESSION['_sf2_attributes']['_security_main'])->getUser();
       
        if ($currentUser->getEmail() === 'admin@transacmoney.com') {
            $depots = $this->depotRepo->findAll();
        } else {
           
            $depots = $this->depotRepo->findByEmail($currentUser->getId());
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
    public function new($user_email, Request $request, CompteRepository $accountUserRepot): Response
    {

        $user = $this->getUserByEmail($user_email);
        $userAdmin =  $this->getUserByEmail("admin@transacmoney.com");


        $depot = new Depot();
        $form = $this->createForm(DepotType::class, $depot);
        $form->handleRequest($request);
        $accountUser = $accountUserRepot->findByUser($user);
        $accountAdmin = $accountUserRepot->findByUser($userAdmin);


        if ($form->isSubmitted() && $form->isValid()) {

            if ($accountUser[0]->getSolde() < 0) {
                $this->addFlash("danger", "Dépot non autorisé. Votre caisse est epuisé. Veuillez contacter l'administrateur.");
            }
            if (intVal($depot->getMontant()) > ($accountUser[0]->getSolde())) {
                $this->addFlash("danger", "Dépot non autorisé. Votre solde est inferieur au montant du dépot. Veuillez contacter l'administrateur.");
            } else {

                // 1. On soustrait le montant du dépot au solde du user-Agence

                $newUserSold = floatVal($accountUser[0]->getSolde() - $depot->getMontant());

                // 2. On calcul la commission

                $montantCommission = floatval($depot->getMontant()) * 0.005;

                // 3. On Increment le compte de l'admin
                $accountAdmin[0]->setSolde($accountAdmin[0]->getSolde() + $depot->getMontant());
                $montantReel = $depot->getMontant() - $montantCommission;

                // 4. On met a jour le compte de l'agent
                $accountUser[0]->setSolde($newUserSold);
                //dd($accountUser[0]->getSolde());
                // 5. On met ajour le montant reel  du depot et sa commissions
                $depot->setMontant($montantReel);
                $depot->setMontantCommission($montantCommission);

                //6. On genere le code
                $codeSecret = str_shuffle(substr(str_shuffle(\md5(str_repeat($depot->getExpediteur()->getTelephone(), 5))), 1, 10));

                //on persist les objets
                $entityManager = $this->getDoctrine()->getManager();
                $depot->setCodeDepot($codeSecret);
                $depot->setUser_depot($user);
                $depot->setStatus(false);
               

                $depot->setMontantCommission($montantCommission);
                $entityManager->persist($depot);
                $entityManager->persist($accountUser[0]);


                $entityManager->flush();
                $this->addFlash('success', 'Le Dépot a été effectué avec succès!');

                return $this->redirectToRoute('depot_index');
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

            return $this->redirectToRoute('depot_index');
        }

        return $this->render('admin/depot/edit.html.twig', [
            'depot' => $depot,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("depot_verification/", name="depot_verification", methods={"GET"})
     * @IsGranted("ROLE_WRITER")
     */
    public function verification(): Response
    {

        return $this->render('admin/depot/verification.html.twig', []);
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

        return $this->redirectToRoute('depot_index');
    }

    /**
     * @Route("/agence/mes-comissions-{user_email}",name="depot_comission",methods={"GET"})
     * @IsGranted("ROLE_WRITER")
     */
    public function getAgencyComissions($user_email)
    {
        $user = $this->getUserByEmail($user_email);

        $comissions = $this->depotRepo->findBy(['user_depot' => $user]);
        $json[] = $comissions;
        $json = json_encode($json, JSON_NUMERIC_CHECK);
        file_put_contents('data.json', $json);
        return $this->render("admin/comission/index.html.twig", compact('comissions'));
    }

    private function getUserByEmail(string $user_email)
    {
        $user = $this->userRepo->findOneByUsernameOrEmail($user_email);
        return $user;
    }

     /**
     * @Route("/depot/imprimer", methods={"GET","POST"},name="depot_report", requirements={"id":"[a-z0-9\-]*"})
     */

    public function report(Request $request, ReportingService $reportingService)
    {
        $code=$request->request->get('code');
        $depot=$this->depotRepo->findOneBy(['codeDepot' => $code]);
        if (!empty($depot)) {
            $reportingService->render($depot,"depot");
        } else {
              $this->addFlash('danger', 'Vous ne pouvez pas imprimer. Car, ce code est invalide!');
              return $this->redirectToRoute('depot_index');
        }
        

       
    }
}
