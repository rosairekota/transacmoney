<?php

namespace App\Controller;

use App\Entity\Depot;
use App\Entity\Compte;
use App\Entity\Search;
use App\Entity\Retrait;
use App\Form\SearchType;
use App\Form\RetraitType;
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
    private $user_email;

    public function __construct(DepotRepository $depoRepo, SessionInterface $session)
    {
        $this->depoRepo = $depoRepo;
        $this->session = $session;
    }
    /**
     * @Route("/@j9a8j7k94.@{user_email}-j7k", name="retrait_index", methods={"GET|POST"})
     *  @IsGranted("ROLE_WRITER")
     */
    public function index($user_email, RetraitRepository $retraitRepository, UserRepository $userRepo, Request $request): Response
    {
        $this->user_email = $user_email;
        if ($user_email == 'admin@transacmoney.com') {
            $retraits = $retraitRepository->findAll();
        } else {
            $user = $userRepo->findOneByUsernameOrEmail($user_email);
            $retraits = $retraitRepository->findByEmail($user->getId());
            //$depots=$depotRepository->selectByIdSql(['id'=>$user->getId()]);


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
     * @Route("/new/@j9a8j7k94.@{user_email}-j7k", name="retrait_new", methods={"GET","POST"})
     *  @IsGranted("ROLE_WRITER")
     */
    public function new($user_email, Request $request, RetraitRepository $retraitRepository, UserRepository $userRepo, DepotRepository $depoRepo, CompteRepository $accountRepot): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $depoUpdate = null;
        // on recupere le depot courant dans la session;
        $depotSession = $this->session->get('depotSession', []);
        $datas = [];
        $retrait = new Retrait();
        $form = $this->createForm(RetraitType::class, $retrait);
        $form->handleRequest($request);
        $user = $userRepo->findOneByUsernameOrEmail($user_email);
        $account = $accountRepot->findByUser($user);


        if ($form->isSubmitted() && $form->isValid()) {
            if ($account[0]->getMontantDebit() < 0 || $account[0]->getMontantDebit() < $retrait->getMontantRetire()) {
                if ($account[0]->getMontantDebit() < 0) {
                    $this->addFlash("danger", "Retrait non autorisé. Votre caisse est epuisé. Veuillez contacter l'administrateur.");
                } elseif ($account[0]->getMontantDebit() < $retrait->getMontantRetire()) {
                    $this->addFlash("danger", "Retrait non autorisé. Votre caise est inferieur du montant de retrait. Veuillez contacter l'administrateur.");
                }
            } else {


                $retrait->setDepot($depotSession['depot'][0]);
                $datas = [
                    "depot_id" => $depotSession['depot'][0]->getId(),
                    "user_retrait_id" => $user->getId(),
                    "montant_retire" => $retrait->getMontantRetire(),
                    "beneficiaire_piece_type" => $retrait->getBeneficiairePieceType(),
                    "beneficiaire_piece_numero" => $retrait->getBeneficiairePieceNumero(),
                    "libelle" => $retrait->getLibelle(),
                    "code_retrait" => $retrait->getCodeRetrait()
                ];

                $montantCommission = $depotSession['depot'][0]->getMontantCommission();
                $credit = $account[0]->getMontantDebit() - $retrait->getMontantRetire();

                $account[0]->setMontantCredit($retrait->getMontantRetire() + $account[0]->getMontantCredit());
                $account[0]->setMontantDebit(floor($credit));
                $solde = (floatval($account[0]->getMontantDebit() - $account[0]->getMontantCredit()));
                //dd($solde);
                $account[0]->setSolde($solde <= 0 ? 0 : $solde);
                // recherche role user
                if ($user->getRoles()[0] == 'ROLE_WRITER') {

                    $salaireSurCommission = round($montantCommission * 0.005, 3);

                    $montantCommissionApres = floor($montantCommission - $salaireSurCommission);

                    $depotSession['depot'][0]->setMontantCommission($montantCommissionApres);

                    $depoUpdate = $depoRepo->updateAmountBySql(
                        [
                            'montant' => $montantCommissionApres,
                            'code_depot' => $retrait->getCodeRetrait()
                        ]
                    );
                    // on verifie si le depot a ete mise a jour
                    if (!empty($depoUpdate)) {

                        $account[0]->setCommissionSousAgent($salaireSurCommission);

                        $account[0]->setUserCompte($user);


                        $entityManager->flush();
                    }
                }
                $retraitRepository->insertBySql($datas);
                unset($depotSession);




                return $this->redirectToRoute('retrait_index', ['user_email' => $user_email]);
            }
        }

        return $this->render('admin/retrait/new.html.twig', [
            'retrait' => $retrait,
            'form' => $form->createView(),
        ]);
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

            return $this->redirectToRoute('retrait_index', ['user_email' => $user_email]);
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

        return $this->redirectToRoute('depot_index', ['user_email' => $user_email]);
    }
}
