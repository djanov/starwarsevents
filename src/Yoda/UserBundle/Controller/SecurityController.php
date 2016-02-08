<?php

namespace Yoda\UserBundle\Controller;

use Yoda\EventBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\security\Core\SecurityContexInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SecurityController extends Controller {

  /**
  * @Route("/login", name="login_form")
  * @Template
  */
  public function loginAction(Request $request)
  {
    $authenticationUtils = $this->get('security.authentication_utils');

    // get the login error if there is one
    $error = $authenticationUtils->getLastAuthenticationError();

    // last username entered by the user
    $lastUsername = $authenticationUtils->getLastUsername();

    return
    array(
      // last username entered by the user
      'last_username' => $lastUsername,
      'error'         => $error,
    );
  }
  /**
   * @Route("/login_check", name="login_check")
   */
  public function loginCheckAction() {
     // not going to put anythig here...
     // this is never executed, Symfony intercepts requests to this
  }

  /**
   * @Route("/logout", name="logout")
   */
  public function logoutAction() {
     // not goint to put anything here
  }


}
