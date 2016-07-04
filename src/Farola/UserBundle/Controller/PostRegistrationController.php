<?php

namespace Farola\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Farola\UserBundle\Form\Type\GetStartedType;
use Farola\ProfileBundle\Form\DescriptionType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Filesystem;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class PostRegistrationController extends Controller
{
   /**
   *    @route("/get-started", name="farola_user_get_started")
   **/
   public function getStartedAction(Request $request) {
   
    if ($this->isGranted('ROLE_USER') == false) {
        return $this->redirectToRoute('farola_home');
    }

    $user = $this->get('security.context')->getToken()->getUser();
    $profile = $user->getCurrentProfile();
    $form = $this->get('form.factory')->create(new GetStartedType(), $profile, array());

    if ($form->handleRequest($request)->isValid())
    {
    	$em = $this->getDoctrine()->getManager();
      $em->persist($profile);
      $em->flush();
    	$this->addFlash('success', "Excellent ".$profile->getName()." ! You can add more details later simply by editing your profile page. Now let's post your first notice !");
      return $this->redirectToRoute('farola_notice_new');
    }
    return $this->render('FarolaUserBundle:PostRegistration:getStarted.html.twig', array('form' => $form->createView()));
   }
}
