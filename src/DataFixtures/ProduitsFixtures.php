<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class ProduitsFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $produit = new Produit();
        $produit->setNom($faker->word)
            ->setDescription($faker->sentence(6, true))
            ->setImage($this->getReference('image4'))
            ->setCategorie($this->getReference('categorie'))
        ;
        $manager->persist($produit);

        $produit1 = new Produit();
        $produit1->setNom($faker->word)
            ->setDescription($faker->sentence(6, true))
            ->setImage($this->getReference('image5'))
            ->setCategorie($this->getReference('categorie1'))
        ;
        $manager->persist($produit1);

        $produit2 = new Produit();
        $produit2->setNom($faker->word)
            ->setDescription($faker->sentence(6, true))
            ->setImage($this->getReference('image7'))
            ->setCategorie($this->getReference('categorie3'))
        ;
        $manager->persist($produit2);

        $produit3 = new Produit();
        $produit3->setNom($faker->word)
            ->setDescription($faker->sentence(6, true))
            ->setImage($this->getReference('image11'))
            ->setCategorie($this->getReference('categorie'))
        ;
        $manager->persist($produit3);

        $produit4 = new Produit();
        $produit4->setNom($faker->word)
            ->setDescription($faker->sentence(6, true))
            ->setImage($this->getReference('image10'))
            ->setCategorie($this->getReference('categorie3'))
        ;
        $manager->persist($produit4);

        $produit5 = new Produit();
        $produit5->setNom($faker->word)
            ->setDescription($faker->sentence(6, true))
            ->setImage($this->getReference('image9'))
            ->setCategorie($this->getReference('categorie2'))
        ;
        $manager->persist($produit5);

        $produit6 = new Produit();
        $produit6->setNom($faker->word)
            ->setDescription($faker->sentence(6, true))
            ->setImage($this->getReference('image8'))
            ->setCategorie($this->getReference('categorie1'))
        ;
        $manager->persist($produit6);

        $produit8 = new Produit();
        $produit8->setNom($faker->word)
            ->setDescription($faker->sentence(6, true))
            ->setImage($this->getReference('image6'))
            ->setCategorie($this->getReference('categorie1'))
        ;
        $manager->persist($produit8);

        $manager->flush();

        $this->addReference('produit', $produit);
        $this->addReference('produit1', $produit1);
        $this->addReference('produit2', $produit2);
        $this->addReference('produit3', $produit3);
        $this->addReference('produit4', $produit4);
        $this->addReference('produit5', $produit5);
        $this->addReference('produit6', $produit6);
        $this->addReference('produit8', $produit8);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 3;
    }
}
