<?php

namespace App\Services;

use App\Entity\Depot;
use Twig\Environment;
use App\Entity\Retrait;
use Spipu\Html2Pdf\Html2Pdf;
use App\Repository\UserRepository;
use App\Repository\RetraitRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Console\Command\Command;

class ReportingService extends Command
{

    private  $twig;
    private $pdf;
    public function __construct(?Environment $twig)
    {

        $this->twig = $twig;
    }


    public function render(Depot $depot,?string $route): string
    {
        $pdf = new Html2Pdf('P', 'A5', 'fr', true, 'UTF-8', 0);
        if ($route==="depot") {
            $html = $this->twig->render('admin/depot/report.html.twig', [
                'depot' => $depot,
            ]);
            $pdf->writeHTML($html);
        } else {
            
            $html = $this->twig->render('admin/retrait/report.html.twig', [
                'depot' => $depot,
            ]);
            $pdf->writeHTML($html);
    
        }
       
        return  $pdf->output();
    }
}
