<?php
namespace App\Model;

use App\Entity\Post;

class Driver{

        public function findAll(){
            $post1 = new Post();
            $post1->setId(1);
            $post1->setSlug("post001");
            $post1->setTitle("Article 1");
            $post1->setExcerpt('Lorem Ipsum is simply dummy text of the printing and typesetting industry.');
            $post1->setCreatedAt(date('d/m/Y'));

            $post2 = new Post();
            $post2->setId(2);
            $post2->setSlug("post002");
            $post2->setTitle("Article 2");
            $post2->setExcerpt('Lorem Ipsum is simply dummy text of the printing and typesetting industry.');
            $post2->setCreatedAt(date('d/m/Y'));

            $post3 = new Post();
            $post3->setId(3);
            $post3->setSlug("post003");
            $post3->setTitle("Article 3");
            $post3->setExcerpt('Lorem Ipsum is simply dummy text of the printing and typesetting industry.');
            $post3->setCreatedAt(date('d/m/Y'));

            $posts = [];
            array_push($posts, $post1, $post2, $post3);
            return $posts;
        }

        public function findOne(int $id){
            $dataPosts = $this->findAll();
            foreach($dataPosts as $post ){
                if($post->getId()==$id){
                    return $post;
                }
            }
        }
}
