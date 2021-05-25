<?php

namespace App\DataFixtures;

use App\Entity\Property;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $property = new Property();
        $property->setName('Maison');
        $property->setDescription('Une super maison');
        $property->setPrice(50000 * 100);
        $manager->persist($property);

        $property = new Property();
        $property->setName('Appartement');
        $property->setDescription('Un super appartement');
        $property->setPrice(30000 * 100);
        $manager->persist($property);

        $property = new Property();
        $property->setName('Studio');
        $property->setDescription('Un super studio');
        $property->setPrice(20000 * 100);
        $manager->persist($property);

        $manager->flush();
    }
}
