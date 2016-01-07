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

// all the stetup is doen

$templating = $container->get('templating');

echo $templating->render(
  'EventBundle:Default:index.html.twig',
  array('name' => 'Vader', 'count' => 3)
);
