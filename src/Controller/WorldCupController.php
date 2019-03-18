<?php



namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class WorldCupController extends AbstractController {
    /**
     * 
     * @Route("/worldcup/winner/{date}/{country}",name="worldcup.winner")
     */
    public function winner(int $date = null, string $country = null ): Response {
        return $this->render('worldcup/winner.html.twig', [
            'date'=>$date,
             'country'=>$country
        ]);
    }
}
