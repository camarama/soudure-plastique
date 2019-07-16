<?php

namespace App\DataFixtures;

use App\Entity\Media;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class MediaFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $image = new Media();
        $image->setAlt('caisse')
            ->setPath($faker->imageUrl(150, 150))
        ;
        $manager->persist($image);

        $image1 = new Media();
        $image1->setAlt('bac')
            ->setPath($faker->imageUrl(150, 150))
        ;
        $manager->persist($image1);

        $image2 = new Media();
        $image2->setAlt('palette')
            ->setPath($faker->imageUrl(150, 150))
        ;
        $manager->persist($image2);

        $image3 = new Media();
        $image3->setAlt('conteneur')
            ->setPath($faker->imageUrl(150, 150))
        ;
        $manager->persist($image3);

        $image4 = new Media();
        $image4->setAlt('caisse gros volume')
            ->setPath($faker->imageUrl(350, 240))
        ;
        $manager->persist($image4);

        $image5 = new Media();
        $image5->setAlt('bac moyen volume')
            ->setPath($faker->imageUrl(350, 240))
        ;
        $manager->persist($image5);

        $image6 = new Media();
        $image6->setAlt('bac moyen volume')
            ->setPath($faker->imageUrl(350, 240))
        ;
        $manager->persist($image6);

        $image7 = new Media();
        $image7->setAlt('conteneur moyen volume')
            ->setPath($faker->imageUrl(350, 240))
        ;
        $manager->persist($image7);

        $image8 = new Media();
        $image8->setAlt('bac avec entré air')
            ->setPath($faker->imageUrl(350, 240))
        ;
        $manager->persist($image8);

        $image9 = new Media();
        $image9->setAlt('palette avec entrée air')
            ->setPath($faker->imageUrl(350, 240))
        ;
        $manager->persist($image9);

        $image10 = new Media();
        $image10->setAlt('conteneur gros volume double')
            ->setPath($faker->imageUrl(350, 240))
        ;
        $manager->persist($image10);

        $image11 = new Media();
        $image11->setAlt('caisse gros volume')
            ->setPath($faker->imageUrl(350, 240))
        ;
        $manager->persist($image11);


        $manager->flush();

        $this->addReference('image', $image);
        $this->addReference('image1', $image1);
        $this->addReference('image2', $image2);
        $this->addReference('image3', $image3);
        $this->addReference('image4', $image4);
        $this->addReference('image5', $image5);
        $this->addReference('image6', $image6);
        $this->addReference('image7', $image7);
        $this->addReference('image8', $image8);
        $this->addReference('image9', $image9);
        $this->addReference('image10', $image10);
        $this->addReference('image11', $image11);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }
}
