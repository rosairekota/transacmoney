<?php

namespace App\Controller;

use App\Entity\Retrait;
use App\Entity\Search;
use App\Form\RetraitType;
use App\Form\SearchType;
use App\Repository\RetraitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/retrait")
 */
class RetraitController extends AbstractController
{ 
     /**
     * @Route("/", name="retrait_index", methods={"GET|POST"})
     *  @IsGranted("ROLE_WRITER")
     */
    public function index(RetraitRepository $retraitRepository,Request $request): Response
    {   $search=new Search();
        $form=$this->createForm(SearchType::class,$search);
        $form->handleRequest($request);
       
        
        if ($form->isSubmitted()&& $form->isValid()) {
            $secretCode=$retraitRepository->findSecretCode($search);
            if (empty($secretCode)) {
                $this->addFlash('success',"ce code($secretCode) est valide.<br> Effectuer le retrait");
                dd($secretCode);
            }
            else{
                $this->addFlash('danger',"ce code(($secretCode) est invalide.<br>  retrait non autorisé !");
            return $this->render('admin/retrait/index.html.twig', [
                'retraits' => $retraitRepository->findAll(),
                'form'     =>$form->createView(),
                '$secretCode'=>$secretCode
            ]);
            }
        }
        return $this->render('admin/retrait/index.html.twig', [
            'retraits' => $retraitRepository->findAll(),
            'form'     =>$form->createView(),
            
        ]);
    }
    /**
     * @Route("/search", name="retrait_search", methods={"GET|POST"})
     *  @IsGranted("ROLE_WRITER")
     */
    public function search(RetraitRepository $retraitRepository,Request $request): Response
    {   $search=new Search();
        $form=$this->createForm(SearchType::class,$search);
        $form->handleRequest($request);
        $response='';
        
        if ($form->isSubmitted()&& $form->isValid()) {
            
            $secretCode=$retraitRepository->findSecretCode($search);
          
            if (!empty($secretCode)) {
                $this->addFlash('success',"$search->code_secret) est valide.Effectuer le retrait");
              
            }
            else{
                $this->addFlash('danger',"$search->code_secret est invalide! retrait non autorisé !");
                $response='Cliquez ici pour retiter votre argent';
            }
        }
        return $this->render('admin/retrait/searchCode.html.twig',[
            'form'     =>$form->createView(),
            'response'=>$response,
            
        ]);
    }

    /**
     * @Route("/new/{user_email}", name="retrait_new", methods={"GET","POST"})
     *  @IsGranted("ROLE_WRITER")
     */
    public function new($user_email,Request $request): Response
    {
        $retrait = new Retrait();
        $form = $this->createForm(RetraitType::class, $retrait);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($retrait);
            $entityManager->flush();

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
     * @Route("/{id}", name="retrait_delete", methods={"DELETE"})
     *  @IsGranted("ROLE_WRITER")
     */
    public function delete(Request $request, Retrait $retrait): Response
    {
        if ($this->isCsrfTokenValid('delete'.$retrait->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($retrait);
            $entityManager->flush();
        }

        return $this->redirectToRoute('retrait_index');
    }
}
