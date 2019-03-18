<?php

/*
 * espace de nom
 * App : espace de nom par defaut, rélié au dossier src
 * Controller : nom du dossier en cours
 */

namespace App\Controller;

use Symfony\Component\Routing\Tests\Fixtures\AnnotationFixtures\AbstractClassController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * tous les controleurs symfony héritent de AbstractController
 */
class DefaultController extends AbstractClassController {
    public function index(Request $request):Response{
        //accéder à la requête http : injecter la requête en paramètre de la méthode
        /*composant debug : composer req debug --dev
         * dump : équilant à var_dump
         * dd : var_dump puis un die
         * 
         * S_POST : $request->request
         * $_GET : $request->query
         * $_SERVER : $request->files
         * $_COOKIES : $request->cookies
         * $_HEADERS : $request->headers
         */
        //dd($request);
        //créer une réponse http
        $response = new Response('<h1>coucou<h1>', 200);
        return $response;
    }
}
