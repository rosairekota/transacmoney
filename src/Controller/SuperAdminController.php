<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SuperAdminController extends AbstractController
{
    /**
     * @Route("/admin/super", name="super_admin")
     */
    public function index(): Response
    {
        return $this->render('super_admin/index.html.twig', [
        ]);
    }
}
