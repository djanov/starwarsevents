<?php

namespace Yoda\EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Yoda\EventBundle\Entity\Event;
use Symfony\Component\Security\Core\Exeption\AccesDeniedException;

class Controller extends BaseController {

  public function getSecurityContext() {
    return $this->container->get('security.context');
  }


  public function enforceOwnerSecurity(Event $event) {
    $user = $this->getUser();

    if ($user != $event->getOwner()) {
      throw $this->createAccessDeniedException('You are not the owner!!!');
    }
  }
}
