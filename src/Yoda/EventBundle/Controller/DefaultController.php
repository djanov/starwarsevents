<?php

namespace Yoda\EventBundle\Controller;


class DefaultController extends Controller
{
  public function indexAction($count, $firstName)
  {

    //get a parameter!
    //$this->container->getParameter('our_assets_version');

    // these 2 lines are equivalent
    // $em = $this->container->get('doctrine')->getManager();
    $em = $this->getDoctrine()->getManager();
    $repo = $em->getRepository('EventBundle:Event');

    $event = $repo->findOneBy(array(
      'name' => 'Darth\'s surprise birthday party',
    ));

    return $this->render(
    'EventBundle:Default:index.html.twig',
    array(
      'name' => $firstName,
      'count' => $count,
      'event'=> $event,
    )
  );
}
}
