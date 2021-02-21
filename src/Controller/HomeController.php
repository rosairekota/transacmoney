<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Twilio\Rest\Client;
class HomeController extends AbstractController{

/**
 * @Route("/", name="home", methods={"GET","POST"})
 */
public function index(Request $request){
  $number=$request->request->get('number');
  $message=$request->request->get('msg');
  
   if (!empty($number)&&!empty($message) ) {
    
    $this->getSms(['number'=>$number,'msg'=>$message]);
   }
    return $this->render("home/index.html.twig",[]);
}

private function getSms(array $datas=[]){
        \extract($datas);
       
            // Your Account SID and Auth Token from twilio.com/console
        $account_sid = 'AC830ecd5e3a13c9a474b5561cbb9e8f3d';
        $auth_token = '6d8e4c7753a0bb8258258a635a5b044e';
        // In production, these should be environment variables. E.g.:
        // $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]

        // A Twilio number you own with SMS capabilities
        $twilio_number = "+18033938444";

        $client = new Client($account_sid, $auth_token);
        $client->messages->create(
            // Where to send a text message (your cell phone?)
            $number,
            [
                'from' => $twilio_number,
                'body' => $msg,
            ]
        );
}
}