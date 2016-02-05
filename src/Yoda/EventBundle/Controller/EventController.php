<?php

namespace Yoda\EventBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Yoda\EventBundle\Entity\Event;
use Yoda\EventBundle\Form\EventType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\Exeption\AccessDeniedExceptionl;

/**
* Event controller.
*
*/
class EventController extends Controller
{
  /**
  * @Route("/", name="event")
  * @Template()
  * Lists all Event entities.
  *
  */
  public function indexAction()
  {
    $em = $this->getDoctrine()->getManager();

    $entities = $em->getRepository('EventBundle:Event')->findAll();

    return array(
      'entities' => $entities,
    );
  }

  /**
  * Creates a new Event entity.
  *
  */
  public function newAction(Request $request)
  {

    //$this->enforceUserSecurity();
    $this->enforceUserSecurity('ROLE_EVENT_CREATE');
    $event = new Event();
    $form = $this->createForm(new EventType(), $event);
    $form->handleRequest($request);

    if ($form->isValid()) {
      $user = $this->getUser();

      $event->setOwner($user);
      $em = $this->getDoctrine()->getManager();
      $em->persist($event);
      $em->flush();

      return $this->redirectToRoute('event_show', array('id' => $event->getId()));
    }

    return $this->render('EventBundle:Event:new.html.twig', array(
      'event' => $event,
      'form' => $form->createView(),
    ));
  }

  /**
  * Finds and displays a Event entity.
  *
  */
  public function showAction(Event $event)
  {
    $deleteForm = $this->createDeleteForm($event);

    return $this->render('EventBundle:Event:show.html.twig', array(
      'event' => $event,
      'delete_form' => $deleteForm->createView(),
    ));
  }

  /**
  * Displays a form to edit an existing Event entity.
  *
  */
  public function editAction(Request $request, Event $event)
  {
    $this->enforceUserSecurity();

    $deleteForm = $this->createDeleteForm($event);
    $editForm = $this->createForm(new EventType(), $event);
    $editForm->handleRequest($request);

    if ($editForm->isSubmitted() && $editForm->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($event);
      $em->flush();

      return $this->redirectToRoute('event_edit', array('id' => $event->getId()));
    }

    return $this->render('event/edit.html.twig', array(
      'event' => $event,
      'edit_form' => $editForm->createView(),
      'delete_form' => $deleteForm->createView(),
    ));
  }

  /**
  * Deletes a Event entity.
  *
  */
  public function deleteAction(Request $request, Event $event)
  {

    $this->enforceUserSecurity();
    $form = $this->createDeleteForm($event);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->remove($event);
      $em->flush();
    }

    return $this->redirectToRoute('event_index');
  }

  /**
  * Creates a form to delete a Event entity.
  *
  * @param Event $event The Event entity
  *
  * @return \Symfony\Component\Form\Form The form
  */
  private function createDeleteForm(Event $event)
  {
    return $this->createFormBuilder()
    ->setAction($this->generateUrl('event_delete', array('id' => $event->getId())))
    ->setMethod('DELETE')
    ->getForm()
    ;
  }

  private function enforceUserSecurity($role = 'ROLE_USER')
  {
      $securityContext = $this->container->get('security.context');
      if (!$securityContext->isGranted($role)) {
          throw $this->createAccessDeniedException('Need ' .$role);
        // in old symfony  throw new AccessDeniedException('Need '.$role);
      }
  }
}
