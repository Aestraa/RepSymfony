<?php

namespace App\DataFixtures;

use App\Entity\Artiste;
use App\Entity\Chanson;
use App\Entity\Type;
use DateTime;
use DateTimeImmutable;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DiscoFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');
        $types = [];
        // Création des types
        $types = ['auteur', 'compositeur', 'interprète', 'arrangeur', 'musicien'];

        $typeEntities = [];
        foreach ($types as $typeName) {
            $type = (new Type())->setType($typeName)
                ->setDescription($faker->text(50));
            $manager->persist($type);
            $typeEntities[] = $type;
        }

        $manager->flush();

        $chansons = [];
        // Création des chansons
        for ($i = 0; $i < 50; $i++) {
            $dateSortie = DateTimeImmutable::createFromMutable($faker->dateTime());
            $chanson = (new Chanson())->setTitre($faker->sentence(4))
                ->setGenre($faker->sentence(1))
                ->setLangue($faker->languageCode(1))
                ->setDateSortie($dateSortie)
                ->setPhotoCouverture("https://picsum.photos/360/360?image=" . ($i + 2));
            $manager->persist($chanson);
            $chansons[] = $chanson;
            $manager->flush();
        }


        $artistes = [];
        for ($i = 0; $i < 50; $i++) {
            // Création des artistes
            $dateNaissance = DateTimeImmutable::createFromMutable($faker->dateTime());
            $artiste = (new Artiste())->setNom($faker->lastName())
                ->setPrenom($faker->firstName())
                ->setLieuNaissance($faker->city())
                ->setPhoto("https://picsum.photos/360/360?image=" . ($i + 25))
                ->setDateNaissance($dateNaissance)
                ->setEtre($typeEntities[rand(0, count($typeEntities) - 1)])
                ->setDescription($faker->text(50))
                ->addParticiper($chansons[rand(0, count($chansons) - 1)]);

            $manager->persist($artiste);
            $artistes[] = $artiste;
            $manager->flush();
        }
    }
}
