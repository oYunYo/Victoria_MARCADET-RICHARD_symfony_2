<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setLastname('DUPOND');
        $admin->setFirstname('Patricia');
        $admin->setSector('RH');
        $admin->setPhoto('patricia.jpg');
        $admin->setEmail('patricia@deloitte.com');

        $password = $this->encoder->encodePassword($admin, 'patricia123@');
        $admin->setPassword($password);
        $admin->setRoles(['ROLE_USER']);

        $manager->persist($admin);
        $manager->flush();

        $admin = new User();
        $admin->setLastname('FITOUSSI');
        $admin->setFirstname('Grégory');
        $admin->setSector('Informatique');
        $admin->setPhoto('gregory.jpg');
        $admin->setEmail('gregory@deloitte.com');

        $password = $this->encoder->encodePassword($admin, 'gregory123@');
        $admin->setPassword($password);
        $admin->setRoles(['ROLE_USER']);

        $manager->persist($admin);
        $manager->flush();

        $admin = new User();
        $admin->setLastname('DUPUIS');
        $admin->setFirstname('Vanessa');
        $admin->setSector('Direction');
        $admin->setPhoto('vanessa.jpg');
        $admin->setEmail('admin@deloitte.com');

        $password = $this->encoder->encodePassword($admin, 'admin123@');
        $admin->setPassword($password);
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);
        $manager->flush();

    }
}