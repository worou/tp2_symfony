<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class DefaultController{

    /**
     * @Route("/", name="home")
     */
    public function index(){
        return new Response("<h1>Hello world!</h1>");
    }

     /**
     * @Route("/about", name="about")
     */
    public function apropos(){
        return new Response("<h1>Page de présentation</h1>");
    }

     /**
     * @Route("/product/{id}")
     */
    public function getProduct($id){
        return new Response("<i>Le produit de numéro N° ".$id." est vendu.</i>");
    }
}