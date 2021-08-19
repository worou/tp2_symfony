<?php

namespace App\Controller;

use App\Model\Driver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/post", name="post")
     */
    public function index(): Response
    {
        $driver = new Driver();
        $tabPosts = $driver->findAll();
       
        return $this->render('post/index.html.twig', [
            'posts' => $tabPosts,
        ]);
    }

    /**
     * @Route("/post/{id}", name="post_item")
    */
    public function postItem(int $id){
        $driver = new Driver();
        $post = $driver->findOne($id);
        //dd($post);
        return $this->render('post/detail.html.twig', ['post'=>$post]);
    }

   
}
