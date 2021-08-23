<?php

namespace App\DataFixtures;

use App\Entity\Auto;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $pays = ["France","Allemagne","Italie", "USA","England"];
        for($i = 1; $i <= 100; $i++){

            $auto = new Auto();
            $auto->setMarque("Marque N° $i");
            $auto->setModele("Modèle N° $i");
            $auto->setPrix(mt_rand(5000,100000));
            $auto->setImage("https://via.placeholder.com/150");
            $auto->setPuissance(mt_rand(100,1000));
            $auto->setPays($pays[array_rand($pays,1)]);

            $manager->persist($auto);
    }

        $manager->flush();
    }
}
