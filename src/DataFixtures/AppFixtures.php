<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Property;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // J'instancie Faker pour générer de fausses données
        $faker = Factory::create('fr_FR');

        $uploadDirectory = __DIR__.'/../../public/uploads/property';

        // Si le dossier existe, on le supprime à chaque lancement des fixtures
        // pour éviter de remplir notre disque dur
        if (is_dir($uploadDirectory)) {
            (new Filesystem())->remove($uploadDirectory);
        }

        // Si le dossier n'existe pas, on le crée pour que faker puisse uploader
        // ses images. On le fait en recursif car si le dossier uploads n'existe pas,
        // le dossier property ne peut pas être créé.
        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0755, true);
        }

        $user = new User();
        $user->setEmail('matthieu@boxydev.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword(
            $this->encoder->encodePassword($user, 'password')
        );
        $this->addReference('user-0', $user);
        $manager->persist($user);

        for ($i = 1; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($faker->email());
            $user->setPassword(
                $this->encoder->encodePassword($user, 'password')
            );
            $this->addReference('user-'.$i, $user);
            $manager->persist($user);
        }

        $categories = ['Maison', 'Appartement', 'Villa', 'Garage', 'Studio'];
        foreach ($categories as $key => $name) {
            $category = new Category();
            $category->setName($name);
            $manager->persist($category);
            $this->addReference('category-'.$key, $category);
        }

        for ($i = 0; $i < 100; $i++) {
            $property = new Property();
            $property->setName($faker->sentence());
            $property->setDescription($faker->text(2000));
            $property->setPrice($faker->numberBetween(34875, 584725) * 100);
            $property->setSurface($faker->numberBetween(10, 400));
            $property->setRooms($faker->numberBetween(1, 5));
            $property->setSold($faker->boolean(25));
            $property->setSlug($faker->slug());
            // On passe le fullpath à false pour avoir
            // toto.jpg au lieu de /Users/matthieu/symfony/public/uploads/toto.jpg
            $property->setImage($faker->image($uploadDirectory, 640, 480, null, false));
            $property->setCategory($this->getReference('category-'.rand(0, 4)));
            $property->setOwner($this->getReference('user-'.rand(0, 9)));
            $manager->persist($property);
        }

        $manager->flush();
    }
}
