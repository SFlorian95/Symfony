<?php



namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/*
 * utilisation de l'annotation @Route pour créer une route 
 * une annotation débute par /**
 * annotation sont lues par le PHP : méta-données associées à la classe
 * 
 * utilisation obligatoire des doubles guillemets
 * paramètres :
 * - schèma de la route : url dans le navigateur
 * - name : identifiant unique de la route; utilisé par les liens
 * nomenclature : nom du controleur puis le nm de la méthode
 */

/* templates : stocker les vues 
 * public : stocker les ressources externes aux vues (js, img..)
 * 
 */


class HelloController extends AbstractController {
 /**
 * @Route("/hello/{firstname}/{age}",name="hello.index")
 */
    
/*
 * varialbe dans l'url : utilisation des accolades
 * les accolades contiennent le nom de la variable
 * les variables d'url se retrouvent en paramètre de la méthode
 * si le paramètre possède une valeur par défaut, la variable d'url devient optionnel
 */
    public function index(string $firstname = 'anonyme', int $age = null):Response{
        /*
         * render : appel d'une vue twig
         * cible le dossier templates
         * second paramètre de render un tableaux associatif
         * permet d'envoyer des informations à la vue 
         * dans la vue, rupération des informations à partir de la clé du tableaux associatif
         */
        
        return $this->render('hello/index.html.twig',[
            'prenom'=>$firstname,
               'age'=>$age
        ]);
        
        //return new Response ('hello');
    }
}
