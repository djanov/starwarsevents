<?php

namespace Yoda\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Yoda\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUsers implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('darth');
        $user->setPassword($this->encodePassword($user, 'darthpass'));
        $manager->persist($user);

        $manager->flush();
    }

    private function encodePassword(User $user, $plainPassword) {
      $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);

      return $encoder->encodePassword($plainPassword, $user->getSalt());
    }

    public function setContainer(ContainerInterface $container = null) {
      $this->container = $container;
    }

}