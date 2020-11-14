<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Offre;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{


     private $passwordEncoder;

     public function __construct(UserPasswordEncoderInterface $passwordEncoder)
     {
         $this->passwordEncoder = $passwordEncoder;
     }



    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');
       

        for ($i=0; $i <= 10 ; $i++) {

            $user = new User();
            $user->setEmail($faker->Email)
                ->setRoles($faker->randomElements(array("ROLE_USER","ROLE_ADMIN"), $count= 1))
                ->setPassword($this->passwordEncoder->encodePassword($user,'123'));

            $manager->persist($user);

            for ($o=0; $o < mt_rand(1,5) ; $o++) { 
                $chrono = 1;
                $offre = new Offre();
                $offre->setNom($faker->name)
                    ->setMission($faker->sentence(6, true))
                    ->setLieu($faker->address)
                    ->setDate( new \DateTime )
                    ->setSalaire($faker->numberBetween(25000,30000))
                    ->setUser($user);

                $manager->persist($offre);
            }
        }

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
