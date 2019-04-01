<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class ProductController extends AbstractController
{
    /**
     * @Route("/products", name="products.index")
     */
    public function index(ProductRepository $productRespository): Response
    {
        /*
         * doctrine : 2 classes principales
         * sélection d'une table : utilisation de la classe repository
         * injecter la classe en paramètre de la méthode du controleur
         * update/delete/insert : classe ObjectManager
         * 
         * dans la classe repository : 4 méthodes de sélection par défaut
         * find(id) : sélection une entité(objet) par son identifiant 
         * findAll() : sélection de toutes les entités: créer un array d'entités 
         * findBy() : sélection de plusieurs entités avec des paramètres
         *  => 
         *      liste de critères : cibler des colonnes et des valeurs 
         *      ordre du tri : cibler une colonne 
         *      limite : limitation du nombre de résultats
         *      offset : décalage des résultats
         * 
         * findOneBy() :  sélection d'une seule entité avec une liste de critères
         */
        /*$result = $productRespository->findOneBy([
            'price'=> 30           
        ]);*/
        //dd($result);
        
        $results = $productRespository->findAll();
        
        return $this->render('product/index.html.twig', [
            'results'=>$results
        ]);       
    }
    
    /**
     * @Route("/product/{id}", name="product.details")
     */
    public function details(int $id,ProductRepository $productRespository): Response{
        //find : selection d'une entité par son identifiant
        $result = $productRespository->find($id);
        //dd($result);
        return $this->render('product/details.html.twig',[
            'result' => $result
        ]);
    }
    
    /**
     * @Route("/products/form", name="product.form")
     * @Route("/products/update/{id}", name="product.update")
     */
    public function form(Request $request, ObjectManager $objectManager, int $id = null, ProductRepository $productRepository ): Response {
        /*
         * affichage d'un formualaire
         *  nécessite 2 instances : classe de formulaire / entité 
         * createform : instancier une classe de formulaire
         * handleRequest: récupération de la saisie dans la $_POST
         */
        
        /*if($id){
            $entity = $productRepository->find($id);
        } else {
            $entity = new Product();
        }*/
        
        $entity = $id ? $productRepository->find($id) : new Product();
        $type = ProductType::class; // renvoie le namespace de la classe
        
        
        
        //ajout d'une propriété dynamique (lors de l'execution) pour stocker
        $entity->prevImage = $entity->getImage();
        //dd($entity);
        
        $form = $this->createForm($type, $entity);
        $form->handleRequest($request); //permet de récuperer le POST
        //dd($request);
        
        /*
         * formulaire valide
         * isValid : formulaire est valide 
         * isSubmitted: formulaire soumis 
         */
        if($form->isSubmitted() && $form->isValid()){
            //pour les champs de type file, supprimer les types dans les getters/setters
            //récupération de l'entité liée au formualaire
            /*
             * transfert d'image 
             *  sécurité : renommer les fichiers 
             *  logique : 
             *      insertion dans le bdd : 
             *           champ file doit etre obligatoire
             *           transfert d'image
             *           mise à jour
             *                  sélection d'un nouveau fichier transfert du nouveau fichier suppression de l'ancien fichier pas de selection
             *                  conserver l'ancien fichier
             * tester l'identifiant pour déterminer la requête sql 
             * null: insertion
             * pas null: mise à jour
             */ 
            if(!$entity->getId()){// si l'identifiant de l'entité est vide
                /*
                 * ramdom_bytes : octets binaires aléatoires
                 *      longueur est multiplié par 2
                 * bin2hex : convertir du binaire vers l'hexa
                 * 
                 * UploadedFile
                 *      guessExtension: récupérer l'extension 
                 *      move: transfert du fichier
                 *              cibler le dossier "public"
                 *              2 paramètres : dossier de destination et nom du fichier
                 */
                $imageName = bin2hex(random_bytes(16));
                //dd($imageName);
                $uploadedFile = $entity->getImage(); // renvoie un objet UploadedFile
                $extension = $uploadedFile->guessExtension();
                $uploadedFile->move('img/', "$imageName.$extension");
                
                // mise à jour de la propriété image 
                 $entity->setImage("$imageName.$extension");     
                //dd($entity,$extension);
                 
                 
            }
            
            //si l'entité est mise à jour et qu'une image n'a pas été sélectionné          
            elseif($entity->getId() && !$entity->getImage()){
                //récupération de la propriété dynamique prevImage pour remplir la propriété image
                $entity->setImage($entity->prevImage);
                
            }
            
            // si l'identifiant est mise a jour et qu'une image a été séléctionné 
            elseif($entity->getId() && $entity->getImage()){
                //unlink : suppression de l'ancienne image
                 unlink("img/{$entity->prevImage}");
                 
                //transfert de la nouvelle image              
                $imageName = bin2hex(random_bytes(16));          
                $uploadedFile = $entity->getImage(); // renvoie un objet UploadedFile
                $extension = $uploadedFile->guessExtension();
                $uploadedFile->move('img/', "$imageName.$extension");              
                 $entity->setImage("$imageName.$extension");                              
            }
            
             /*
                  * message flash : message en session suppprimé après l'affichage
                  * addFlash : 2 paramètres
                  *     clé de l'entrée dans la session
                  *     valeur
                  */
                 $message=$entity->getId() ? "Le produit a été modifié" : "le produit a été ajouté";
                 $this->addFlash('notice', $message);
            
             /* 
             * ObjectManager permet de gérer les entités (insert, delete, update)
             *  persist(): mettre en file d'attente la requête SQL
             *  flush(): exécuter les requêtes SQL en file d'attente
             */
            
            //dd('formulaire valide', $entity);
            
            $objectManager->persist($entity);
            $objectManager->flush();
            
                 
                 
                 //redirectToRoute : redirection
                 
                 return $this->redirectToRoute('products.index');
        }
        
        //createView : convertir la classe en champ de saisie HTML 
        
        return $this->render('product/form.html.twig',[
            'form' => $form->createView()
        ]);
    }
    
    // supression d'un produit
    /**
     * @Route("/products/delete/{id}", name="product.delete")
     * 
     */
    public function delete(int $id, ProductRepository $productRepository, ObjectManager $objectManager): Response {
        //selection de l'entité par son identifiant
        $entity = $productRepository->find($id);
        
        //suppression de l'entité
        $objectManager->remove($entity);
        $objectManager->flush();
        
        //suppression de l'image
        unlink("img/{$entity->getImage()}");
        
        //message
        $this->addFlash('notice', "le produit a été supprimé");
        
        //redirection
        return $this->redirectToRoute('products.index');
    }
}
