<?php

namespace App\Controller;

use App\Entity\Depot;
use App\Services\ReportingService;
use Twig\Environment;
use App\Entity\Compte;
use App\Entity\Search;
use App\Entity\Retrait;
use App\Form\SearchType;
use App\Form\RetraitType;
use Spipu\Html2Pdf\Html2Pdf;
use App\Repository\UserRepository;
use App\Repository\DepotRepository;
use App\Repository\CompteRepository;
use App\Repository\RetraitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/retrait")
 */
class RetraitController extends AbstractController
{
    private SessionInterface $session;
    private $depoRepo;
   

    public function __construct(DepotRepository $depoRepo, SessionInterface $session)
    {
        $this->depoRepo = $depoRepo;
        $this->session = $session;
        
    }
    /**
     * @Route("/mes-retraits", name="retrait_index", methods={"GET|POST"})
     *  @IsGranted("ROLE_WRITER")
     */
    public function index(RetraitRepository $retraitRepository, UserRepository $userRepo, Request $request): Response
    {
         $currentUser = unserialize($_SESSION['_sf2_attributes']['_security_main'])->getUser();
        
        if ($currentUser->getEmail() === 'admin@transacmoney.com') {
            $retraits = $retraitRepository->findAll();
        } else {
            $retraits = $retraitRepository->findByEmail($currentUser->getId());

        }
        return $this->render('admin/retrait/index.html.twig', [
            'retraits' => $retraits,

        ]);
    }
    /**
     * @Route("/search", name="retrait_search", methods={"GET|POST"})
     *  @IsGranted("ROLE_WRITER")
     */
    public function search(RetraitRepository $retraitRepository, Request $request): Response
    {
        $depotSession = $this->session->get('depotSession', []);
        $search = new Search();
        $depo = new Depot();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);
        $response = '';

        if ($form->isSubmitted() && $form->isValid()) {

            $secretCode = $retraitRepository->findSecretCode($search);
            $depot = $this->depoRepo->findSecretCodeByOrm($search);

            if (empty($secretCode) && !empty($depot)) {
                $this->addFlash('info', "$search->code_secret) est valide. Effectuer le retrait");
                $response = 'Cliquez ici pour continuer ';

                $depotSession = ['depot' => $depot];

                $this->session->set('depotSession', $depotSession);
                return $this->redirectToRoute('retrait_preview', ['id' => $depot[0]->getId()]);
            } else {
                $this->addFlash('danger', "$search->code_secret est invalide! retrait non autorisé !");
            }
        }
        return $this->render('admin/retrait/searchCode.html.twig', [
            'form'     => $form->createView(),
            'response' => $response,

        ]);
    }
    /**
     * @Route("/previsualisation/{id}", name="retrait_preview", methods={"GET","POST"})
     *  @IsGranted("ROLE_WRITER")
     */
    public function previewInfoRetrait(Depot $depot)
    {
        $this->addFlash('success', "Code valide. Effectuer le retrait");
        return $this->render('admin/retrait/preview.html.twig', [
            'depot'     => $depot,
        ]);
    }
    /**
     * @Route("/nouveau-retrait", name="retrait_new", methods={"GET","POST"})
     *  @IsGranted("ROLE_WRITER")
     */
    public function new(Request $request, RetraitRepository $retraitRepository, UserRepository $userRepo, DepotRepository $depoRepo, CompteRepository $accountRepot): Response
    {   
        


        $depoUpdate = null;
        // on recupere le depot courant dans la session;
        $depotSession = $this->session->get('depotSession', []);
        $datas = [];
        $retrait = new Retrait();
        $form = $this->createForm(RetraitType::class, $retrait);
        $form->handleRequest($request);

       // fetching  compte admin et user courant
        $currentUser = unserialize($_SESSION['_sf2_attributes']['_security_main'])->getUser();
        $adminUser = $userRepo->findOneByUsernameOrEmail("admin@transacmoney.com");
        $accountCurrentUser = $accountRepot->findOneByUser($currentUser);
        $adminAccount=$accountRepot->findOneBy(['id'=>$adminUser->getAccount()->getId()]);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // $retrait->setDepot($depotSession['depot'][0]);
            $datas = [
                "depot_id" => $depotSession['depot'][0]->getId(),
                "user_retrait" => $currentUser->getId(),
                "montant_retire" => $retrait->getMontantRetire(),
                "beneficiaire_piece_type" => $retrait->getBeneficiairePieceType(),
                "beneficiaire_piece_numero" => $retrait->getBeneficiairePieceNumero(),
                "libelle" => $retrait->getLibelle(),
                "code_retrait" => $retrait->getCodeRetrait()
            ];

            // traitement des comptes: 
            $entityManager=$this->getDoctrine()->getManager();

            $adminAccount->setSolde($adminAccount->getSolde() - $retrait->getMontantRetire());
            $accountCurrentUser->setSolde($accountCurrentUser->getSolde() + $retrait->getMontantRetire());
            $entityManager->persist($adminAccount);
            $entityManager->persist($accountCurrentUser);
            $entityManager->flush();
        
            $insertRetrait = $retraitRepository->insertBySql($datas);
            if (!empty($insertRetrait)) {
                $newRetrait = $retraitRepository->findOneBy(['code_retrait' => $retrait->getCodeRetrait()]);
                if (!empty($newRetrait)) {
                    $depoUpdate = $depoRepo->updateAmountBySql(
                        ['retrait' => $newRetrait->getId(),
                         'code_depot' => $retrait->getCodeRetrait(),
                         'status'=>true
                    ]);
                    //     // on verifie si le depot a ete mise a jour
                    if (!empty($depoUpdate)) {
                        $this->addFlash('success','votre Compte vient dêtre credité de'.$retrait->getMontantRetire().'$');
                    }
                }
            } else {
                $this->addFlash('warning', "Echec d'insertion");
            }
            unset($depotSession);
            return $this->redirectToRoute('retrait_index');
        }

        return $this->render('admin/retrait/new.html.twig', [
            'retrait' => $retrait,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/retrait/report/", methods={"GET","POST"},name="retrait_report", requirements={"id":"[a-z0-9\-]*"})
     */

    public function report(Request $request, DepotRepository $depotRepo,ReportingService $reportingService)
    {
       
        $code=$request->request->get('code');
        $depot=$depotRepo->findOneBy(['codeDepot' => $code]);
        if (!empty($depot)) {
            $reportingService->render($depot,"retrait");
        } else {
             $this->addFlash('danger', 'Vous ne pouvez pas imprimer. Car, ce code est invalide!');
              return $this->redirectToRoute('retrait_index');
        }
    }
    /**
     * @Route("/new/{user_email}", name="retrait_new_old", methods={"GET","POST"})
     *  @IsGranted("ROLE_WRITER")
     */
    public function new2($user_email, Request $request, UserRepository $userRepo): Response
    {
        $depotSession = $this->session->get('depotSession', []);

        $retrait = new Retrait();
        $form = $this->createForm(RetraitType::class, $retrait);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $user = $userRepo->findOneByUsernameOrEmail($user_email);

            $retrait->setDepot($depotSession['depot'][0]);
            $retrait->setUserRetrait($user);
            $entityManager->persist($retrait);
            $entityManager->flush();
            unset($depotSession);
            session_destroy();

            return $this->redirectToRoute('retrait_index');
        }

        return $this->render('admin/retrait/new.html.twig', [
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
     *  @IsGranted("ROLE_WRITER")
     */
    public function edit(Request $request, Retrait $retrait): Response
    {
        $form = $this->createForm(RetraitType::class, $retrait);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('retrait_index');
        }

        return $this->render('admin/retrait/edit.html.twig', [
            'retrait' => $retrait,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{user_email}-@j9a8j7k94", name="retrait_delete", methods={"DELETE"})
     *  @IsGranted("ROLE_WRITER")
     */
    public function delete($user_email, Request $request, Retrait $retrait): Response
    {
        if ($this->isCsrfTokenValid('delete' . $retrait->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($retrait);
            $entityManager->flush();
        }

        return $this->redirectToRoute('retrait_index');
    }
}
