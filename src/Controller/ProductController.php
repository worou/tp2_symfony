<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product_home")
     */
    public function index(): Response
    {
        $products = [
            ["id" => 1, "title"=>"Article1", "image"=>"image1.jpg", "description"=>"Description1", "date_parution"=>"12/12/2015" ],
            ["id" => 2, "title"=>"Article2", "image"=>"image2.jpg", "description"=>"Description2", "date_parution"=>"12/12/2020" ],
            ["id" => 3, "title"=>"Article3", "image"=>"image3.jpg", "description"=>"Description3", "date_parution"=>"11/12/2015" ],
            ["id" => 4, "title"=>"Article4", "image"=>"image4.jpg", "description"=>"Description4","date_parution"=>"11/12/2019" ]
        ];
        return $this->render('product/index.html.twig', [
           "products"=>$products
        ]);
    }

    /**
     * @Route("/presentation", name="product_about")
     */
    public function aboutAction(){
        return $this->render('product/about.html.twig');
    }

      /**
     * @Route("/contact", name="product_contact")
     */
    public function contactAction(){
        return $this->render('product/contact.html.twig');
    }

    
}
