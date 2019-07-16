<?php

namespace App\DataFixtures;

use App\Entity\Commande;
use App\Entity\Info;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UtilisateursFixtures extends Fixture implements OrderedFixtureInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i <= 10; $i++)
        {
            $utilisateur =  new Utilisateur();
            $utilisateur->setNom($faker->company)
                ->setEmail($faker->companyEmail)
                ->setRoles(['ROLE_USER'])
                ->setPassword($this->encoder->encodePassword($utilisateur, 'test'))
            ;
            $manager->persist($utilisateur);

            for ($j = 0; $j <= 10; $j++)
            {
                $info = new Info();
                $info->setSiret($faker->siret)
                    ->setTelephone($faker->phoneNumber)
                    ->setAdresse($faker->streetAddress)
                    ->setCp($faker->postcode)
                    ->setVille($faker->city)
                    ->setPays('FRANCE')
                    ->setUtilisateur($utilisateur)
                ;
                $manager->persist($info);

                for ($k = 0; $k <= mt_rand(2, 6); $k++)
                {
                    $commande = new  Commande();
                    $commande->setReference($faker->randomNumber(null, false))
                        ->setDate($faker->dateTimeBetween('-2years', 'now'))
                        ->setUtilisateur($utilisateur)
                        ->setProduits([
                            '0' => [
                                $this->getReference('produit') => '5'
                            ],
                            '1' => [
                                $this->getReference('produit1') => '3'
                            ],
                            '2' => [
                                $this->getReference('produit2') => '7'
                            ],
                            '3' => [
                                $this->getReference('produit3') => '2'
                            ],
                            '4' => [
                                $this->getReference('produit4') => '4'
                            ],
                            '5' => [
                                $this->getReference('produit5') => '6'
                            ],
                            '6' => [
                                $this->getReference('produit6') => '9'
                            ],
                            '7' => [
                                $this->getReference('produit8') => '8'
                            ]
                        ])
                    ;
                    $manager->persist($commande);
                }
            }
        }

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 4;
    }
}
