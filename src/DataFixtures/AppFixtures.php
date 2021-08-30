<?php

namespace App\DataFixtures;

use App\Entity\Auto;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $pays = ["France","Allemagne","Italie", "USA","England"];
        $categories = [];
        $images =['1629871099.jpg','1629871129.jpg','1629875741.jpg','1629878571.jpg'];

           $cat1 = new Category();
           $cat1->setName('Luxe');
           $cat1->setCreatedAt(new \DateTimeImmutable());

           $cat2 = new Category();
           $cat2->setName('Neuve');
           $cat2->setCreatedAt(new \DateTimeImmutable());

           $cat3 = new Category();
           $cat3->setName('Sport');
           $cat3->setCreatedAt(new \DateTimeImmutable());
           array_push($categories, $cat1, $cat2, $cat3);
       
        for($i = 1; $i <= 100; $i++){
            
            $auto = new Auto();
            $auto->setMarque("Marque N° $i");
            $auto->setModele("Modèle N° $i");
            $auto->setPrix(mt_rand(5000,100000));
            $auto->setImage($images[array_rand($images,1)]);
            $auto->setPuissance(mt_rand(100,1000));
            $auto->setPays($pays[array_rand($pays,1)]);
            $auto->setDescription("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,");
            $auto->setCategory($categories[array_rand($categories,1)]);
            $manager->persist($categories[array_rand($categories,1)]);
            $manager->persist($auto);
    }

        $manager->flush();
    }
}
