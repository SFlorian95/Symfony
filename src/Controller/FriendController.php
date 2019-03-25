<?php

namespace App\Controller;

use App\Repository\FriendRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FriendController extends AbstractController {

    /**
    * @Route("/friend", name="friend.liste")
    */

    public function friend(FriendRepository $friendrepository): Response{
        
        $amis = $friendrepository->findAll();
        //dd($amis);
        
    return $this->render("friend/friendslist.html.twig", [
        'liste' => $amis
    ]);

    }

    /**
    * @Route("/friend/{id}", name="friend.id")
    */

    public function id(int $id, FriendRepository $friendrepository){
        /* toujours mettre la variable dans les parametre de la fonction. */
        /*dd($this->liste[$id]);*/
        
        $amis = $friendrepository->find($id);
        
        return $this->render("friend/id.html.twig", [
            'details' => $amis

        ]);

    }
    
    





}