<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class CategoriesFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $categorie = new Categorie();
        $categorie->setNom('caisse')
            ->setImage($this->getReference('image'))
        ;
        $manager->persist($categorie);

        $categorie1 = new Categorie();
        $categorie1->setNom('bac')
            ->setImage($this->getReference('image1'))
        ;
        $manager->persist($categorie1);

        $categorie2 = new Categorie();
        $categorie2->setNom('palette')
            ->setImage($this->getReference('image2'))
        ;
        $manager->persist($categorie2);

        $categorie3 = new Categorie();
        $categorie3->setNom('conteneur')
            ->setImage($this->getReference('image3'))
        ;
        $manager->persist($categorie3);

        $manager->flush();

        $this->addReference('categorie', $categorie);
        $this->addReference('categorie1', $categorie1);
        $this->addReference('categorie2', $categorie2);
        $this->addReference('categorie3', $categorie3);

    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }
}
