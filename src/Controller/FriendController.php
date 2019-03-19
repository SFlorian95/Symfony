<?php



namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class FriendController extends AbstractController {
   
    
    
    /**
    * 
    * @Route("/friend",name="friend.index")
    */
    
    public function index(): Response{
        
        $list=[
        'Subra Florian' => "'friend.details',{nom: 'Subra', prenom: 'Florian',email: 'monemail1@hotmail.fr'}",
        'Bond James' => "'friend.details',{nom: 'Bond', prenom: 'James',email: 'monemail2@hotmail.fr'}",
        'Wayne Bruce' => "'friend.details',{nom: 'Wayne', prenom: 'Bruce',email: 'monemail3@hotmail.fr'}",
        'Jordan Hal' => "'friend.details',{nom: 'Jordan', prenom: 'Hal',email: 'monemail4@hotmail.fr'}" 
        ];
        
        return $this->render('friend/index.html.twig',[
            'list' => $list
        ]);   
    }
    
    
    /**
     * 
     * @Route("/friend/{id}/{nom}/{prenom}/{email}",name="friend.details") 
     */
    public function details(string $nom , string $prenom, string $email): Response{
        
       
        
        return $this->render('friend/details.html.twig',[
            
            'nom'=> $nom,
            'prenom'=> $prenom,
            'email'=> $email
        ]); 
    }
}
