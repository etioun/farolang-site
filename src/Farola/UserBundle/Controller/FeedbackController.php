<?php

namespace Farola\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Farola\UserBundle\Form\Type\FeedbackType;
use Farola\UserBundle\Entity\Feedback;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Filesystem\Filesystem;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class FeedbackController extends Controller
{
   /**
   *    @route("/feedback", name="farola_user_feedback")
   **/
   public function feedbackAction(Request $request) {
    $user = $this->get('security.context')->getToken()->getUser();
    $newFeedback = new Feedback($user);
    $form = $this->get('form.factory')->create(new FeedbackType, $newFeedback, array());

    if ($form->handleRequest($request)->isValid())
    {
    	$em = $this->getDoctrine()->getManager();
      $em->persist($newFeedback);
      $em->flush();
    	$this->addFlash('success', 'Thanks for contacting us ! Your message has been sent succesfully !');
      return $this->redirectToRoute('farola_home');
    }

    return $this->render('FarolaUserBundle:Feedback:feedback.html.twig', array('form' => $form->createView()));
   }
}
