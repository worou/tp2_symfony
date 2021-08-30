<?php
namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController{

    /**
     * @Route("/", name="home")
     */
    public function index(CategoryRepository $repo, SessionInterface $session){
        $categories = $repo->findAll();
        $session->set('category', $categories);
        //dd($session->get('category'));
        //return new Response("<h1>Hello world!</h1>");
        return $this->render('pages/index.html.twig');
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