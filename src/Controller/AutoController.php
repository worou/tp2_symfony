<?php

namespace App\Controller;

use App\Entity\Auto;
use App\Repository\AutoRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AutoController extends AbstractController
{
    /**
     * @Route("/auto", name="auto")
     */
    public function index(): Response
    {
        
        $repo = $this->getDoctrine()->getRepository(Auto::class);
        $autos = $repo->findAll();
        //dd($autos);
        return $this->render('auto/index.html.twig', [
            'autos' => $autos,
        ]);
    }

    /**
     * @Route("/auto/{id}", name="auto_item")
     */
    // public function getAuto(int $id){
        
    //     $repo = $this->getDoctrine()->getRepository(Auto::class);
    //     $auto = $repo->find($id);
       
    //     return $this->render('auto/detail.html.twig', ['auto'=>$auto]);
    // }

    
    /**
     * @Route("/auto/{id}", name="auto_item")
     */
    // public function getAuto(AutoRepository $repo, $id){

    //     $auto = $repo->find($id);

    //     return $this->render('auto/detail.html.twig', ['auto'=>$auto]);
    // }

    /**
     * @Route("/auto/{id}", name="auto_item")
     */
    public function getAuto(Auto $auto){
        return $this->render('auto/detail.html.twig', ['auto'=>$auto]);
    }

    /**
     * @Route("/new", name="auto_new")
     */
    public function create(){

        $em = $this->getDoctrine()->getManager();

        $auto = new Auto();
        $auto->setMarque("Ford");
        $auto->setModele("Mustang");
        $auto->setPuissance(421);
        $auto->setPrix(100000);
        $auto->setPays("U.S.A");
        $auto->setImage("https://via.placeholder.com/150/0000FF/808080 ?Ford-Mustang");

        $em->persist($auto);
        $em->flush();

        return $this->redirectToRoute("auto");
    }
}
