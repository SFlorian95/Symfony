<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\ProductRepository;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;



class CommentController extends AbstractController
{
    /**
     * @Route("/comment/add", name="comment.add")
     */
    public function add(Request $request, ProductRepository $productRepository, ObjectManager $objectManager): JsonResponse
    {
      /*
       * récupération de $_POST : propriété request de la requête 
       * récupération à partir de l'attribut name du champ 
       */
        $content = $request->request->get('content');
        $id = $request->request->get('id');
        
        //dd($content, $id);
        
        /*
         * insertion du commentaie dans la table
         *  pour enregistrer une entrée dans une table, avec doctrine, il faut créer une instance d'une entitée et utiliser les setters
         */
        
        $comment = new Comment();
        $comment->setContent($content);
        $comment->setDatetime(new DateTime() );
        /*
         * avec doctrine, pour relier des entités, il faut des instances d'entités
         */
        $product = $productRepository->find($id);
        
        // associer une entité à une autre entité : utiliser une entité dans une méthode de l'autre entité 
        $comment->setProduct($product);
        
        //enregistement de la table
        $objectManager->persist($comment);
        $objectManager->flush();
        
        /*
         * reponse http en json 
         * réexecuter la requête pour récupérer les derniers commentaires
         * json_encode: par défaut, ne converti pas en json les objets
         *      obligatoire de renvoyer des arrays
         * toArray : methode de doctrine qui permet de transformer des listes d'entités en array  
         */
        $product = $productRepository->find($id);
        $response = new JsonResponse($product->getComments()->toArray() );
        
        //dd($product->getComments()->toArray() );
        
        return $response;
        
        //dd($comment);
    }
}
 