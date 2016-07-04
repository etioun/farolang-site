<?php

namespace Farola\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Farola\UserBundle\Form\Type\PreferenceType;
use Farola\ProfileBundle\Form\DescriptionType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Filesystem\Filesystem;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class PreferenceController extends Controller
{
   /**
   *    @route("/preference", name="farola_user_preference")
   **/
   public function editAction(Request $request) {
    $user = $this->get('security.context')->getToken()->getUser();
    $form = $this->get('form.factory')->create(new PreferenceType, $user, array());

    if ($form->handleRequest($request)->isValid())
    {
    	$this->getDoctrine()->getManager()->flush();
    	$this->addFlash('success', 'Your account preferences have been updated successfully');
    }

    return $this->render('FarolaUserBundle:Preference:preference.html.twig', array('form' => $form->createView()));
   }
}
