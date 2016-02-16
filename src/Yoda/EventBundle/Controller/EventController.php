<?php

namespace Yoda\EventBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Yoda\EventBundle\Entity\Event;
use Yoda\EventBundle\Form\EventType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\Exeption\AccessDeniedExceptionl;
use Symfony\Component\HttpFoundation\JsonResponse;

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

    return array();
  }

  /**
  * Creates a new Event entity.
  *
  */

  public function _upcomingEventsAction($max = null) {
    $em = $this->getDoctrine()->getManager();

    $events = $em->getRepository('EventBundle:Event')
          ->getUpcomingEvents($max);

    return $this->render('EventBundle:Event:_upcomingEvents.html.twig', array(
      'events' => $events,
    ));
  }

  public function newAction(Request $request)
  {

    //$this->enforceUserSecurity();
    $this->enforceUserSecurity('ROLE_EVENT_CREATE');
    $event = new Event();
    $form = $this->createForm(new EventType(), $event);
    $form->handleRequest($request);

    if ($form->isValid()) {
      $user = $this->getUser();
      // this works!
      // this is setting the owning side of relationship ManyToOne or OneToMany wich means its alway singular side
      $event->setOwner($user);

      // this would do nothing
      // it sets the inverse side
      // $events = $user->getEvents();
      // $events[] = $entity;
      // $user->setEvents($events);

      $em = $this->getDoctrine()->getManager();
      $em->persist($event);
      $em->flush();

      return $this->redirectToRoute('event_show', array('slug' => $event->getSlug()));
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
  public function showAction($slug)
  {
    $em = $this->getDoctrine()->getManager();

    $event = $em->getRepository('EventBundle:Event')
        ->findOneBy(array('slug' => $slug));

    if (!$event) {
      throw $this->createNotFoundException('Unable to find Event entity.');
    }

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

   $this->enforceOwnerSecurity($event);

    $deleteForm = $this->createDeleteForm($event);
    $editForm = $this->createForm(new EventType(), $event);
    $editForm->handleRequest($request);

    if ($editForm->isSubmitted() && $editForm->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($event);
      $em->flush();

      return $this->redirectToRoute('event_edit', array('id' => $event->getId()));
    }

    return $this->render('EventBundle:Event:edit.html.twig', array(
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

      $this->enforceOwnerSecurity($event);

      $em->remove($event);
      $em->flush();
    }

    return $this->redirectToRoute('event_index');
  }

  public function attendAction($id, $format) {
    $em = $this->getDoctrine()->getManager();

    $event = $em->getRepository('EventBundle:Event')
        ->find($id);

    if (!$event) {
      throw $this->createNotFoundException('No event found for id ' . $id);
    }
    if (!$event->hasAttendee($this->getUser())) {
         $event->getAttendees()->add($this->getUser());
    }
    $em->persist($event);
    $em->flush();

    return $this->createAttendingResponse($event, $format);
  }

  public function unattendAction($id, $format) {
    $em = $this->getDoctrine()->getManager();

    $event = $em->getRepository('EventBundle:Event')
        ->find($id);

    if (!$event) {
      throw $this->createNotFoundException('No event found for id ' . $id);
    }
    if ($event->hasAttendee($this->getUser())) {
         $event->getAttendees()->removeElement($this->getUser());
    }
    $em->persist($event);
    $em->flush();

    return $this->createAttendingResponse($event, $format);
  }

   private function createAttendingResponse(Event $event, $format) {
     if ($format == 'json') {
       $data = array(
         'attending' => $event->hasAttendee($this->getUser()),
       );

       $response = new JsonResponse($data);

       return $response;
     }

     $url = $this->generateUrl('event_show', array(
       'slug' => $event->getSlug(),
     ));

     return $this->redirect($url);
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
      if (!$this->getSecurityContext()->isGranted($role)) {
          throw $this->createAccessDeniedException('Need ' .$role);
        // in old symfony  throw new AccessDeniedException('Need '.$role);
      }
  }

}
