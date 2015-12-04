<?php

namespace UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UserBundle\Entity\User;

class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user1 = new User();
        $user1->setUsername("Dieter");
        $user1->setFirstname("Dieter");
        $user1->setLastname("Mustermann");
        $user1->setEmail("dieter.mustermann@gmx.at");

        $user2 = new User();
        $user2->setUsername("Rudolf");
        $user2->setFirstname("Rudolf");
        $user2->setLastname("Mustermann");
        $user2->setEmail("rudolf.mustermann@gmx.at");

        $user3 = new User();
        $user3->setUsername("Herbert");
        $user3->setFirstname("Herbert");
        $user3->setLastname("Mustermann");
        $user3->setEmail("herbert.mustermann@gmx.at");

        $user4 = new User();
        $user4->setUsername("Hermine");
        $user4->setFirstname("Dieter");
        $user4->setLastname("Musterfrau");
        $user4->setEmail("hermine.musterfrau@gmx.at");

        $manager->persist($user1);
        $manager->persist($user2);
        $manager->persist($user3);
        $manager->persist($user4);

        $manager->flush();
    }
}
