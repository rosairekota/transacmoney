<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home_index")
     */
    public function index(): Response
    {
        return $this->redirectToRoute('home_page',[],301);
    }

     /**
     * @Route("/fr", name="home_page")
     */
    public function home(): Response
    {
        return $this->render('home.html.twig', []);
    }
}
