<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;


/**
 * @var Composer\Autoload\ClassLoader $loader
 */
$loader = require __DIR__.'/app/autoload.php';
Debug::enable();

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$kernel->boot();

$container = $kernel->getContainer();
$container->enterScope('request');
$container->set('request', $request);

// all the stetup is done

use Doctrine\ORM\EntityManager;
/** @var EntityManager $em */
$em = $container->get('doctrine')->getManager();

$wayne = $em
  ->getRepository('UserBundle:User')
  ->findOneByUsernameOrEmail('wayne');

$wayne->setPlainPassword('new');
$em->persist($wayne);
$em->flush();

// foreach ($wayne->getEvents() as $event) {
//   var_dump($event->getName());
// }
