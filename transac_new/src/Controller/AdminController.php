<?php


namespace App\Controller;


use App\Repository\UserRepository;
use App\Repository\DepotRepository;
use App\Repository\AgenceRepository;
use App\Repository\RetraitRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{

    /**
     * @Route("/admin",name="app_admin_index")
     */
    public function index(DepotRepository $depotRepository,AgenceRepository $agenceRepository,RetraitRepository $retraitRepository,UserRepository $userRepo){
        

        return $this->render("admin/main.html.twig",[
            'depots'=>$depotRepository->findSumDepotsBySql('depot','montant'),
            'retraits'=>$depotRepository->findSumDepotsBySql('retrait','montant_retire'),
            'agences'=>$agenceRepository->findAll(),
            'users'=>$userRepo->findAll()
        ]);
    }

}
