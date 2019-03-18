<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Component\HttpFoundation\Request;


class TwigController extends AbstractController {
    /**
     * 
     * @Route("/twig", name="twig.index")
     */
    public function index():Response{
        
        //données à envoyer à la vue 
        $list=[
        'key0' => 'value0',
        'key1' => 'value1',
        'key2' => 'value2',
        'key3' => 'value3' 
        ];
        
        $list2 = ['valeur0','valeur1'];
        
        // \ espace de nom global du PHP pour les classes natives 
        $now = new \DateTime();
        // pour envoyer des données à la vue, utiliser un tableau associatif,
        //c'est la clé qui est récupérée dans la vue 
        return $this->render('twig/index.html.twig',[
            'list' => $list,
            'list2' => $list2,
            'aujourdhui'   => $now
        ]); //render = go in template = view
    }
}
