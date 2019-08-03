<?php

namespace App\DataFixtures;


use App\Entity\Info;
use App\Entity\Service;
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
                    $service = new Service();
                    $service->setReference($faker->randomNumber(null, false))
                        ->setDate($faker->dateTimeBetween('-2years', 'now'))
                        ->setUtilisateur($utilisateur)
                        ->setPrestation([
                            '0' => [
                                '1' => '5'
                            ],
                            '1' => [
                                '2' => '3'
                            ],
                            '2' => [
                                '3' => '7'
                            ],
                            '3' => [
                                '4' => '2'
                            ],
                            '4' => [
                                '5' => '4'
                            ],
                            '5' => [
                                '6' => '6'
                            ],
                            '6' => [
                                '7' => '9'
                            ],
                            '7' => [
                                '8' => '8'
                            ]
                        ])
                    ;
                    $manager->persist($service);
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
