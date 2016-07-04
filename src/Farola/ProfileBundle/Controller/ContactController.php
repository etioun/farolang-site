<?php

namespace Farola\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Farola\ProfileBundle\Form\ReviewType;
use Farola\ProfileBundle\Entity\Review;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Farola\ProfileBundle\Entity\Relation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;



class ContactController extends Controller
{
    /**
    *   @Route("/contact", name="farola_profile_contact")
    */
    public function listAction( Request $request)
    {
        $ph = $this->get('farola_profile.helper');
        $em = $this->get('doctrine.orm.entity_manager');
        
        $profiles = $em->getRepository('FarolaProfileBundle:Profile')->getContactsOf($ph->getCurrentUserProfile());
        
        return $this->render('FarolaProfileBundle:Contact:contact-list.html.twig',
            array('profiles' => $profiles));
    }

    /**
    *   @Route("/delete-contact/{profileId}", name="farola_profile_delete_contact_ajax")
    *   @Method({"POST"})
    */
    public function ajaxDeleteAction( $profileId, Request $request)
    {
        $ph = $this->get('farola_profile.helper');
        $em = $this->get('doctrine.orm.entity_manager');
        $contact = $em->getRepository('FarolaProfileBundle:Relation')
            ->findOneBy(array(
                'relatedProfile'=>$profileId, 
                'relOwner'=> $ph->getCurrentUserProfile(), 
                'relationType'=> Relation::TYPE_CONTACT));

        $em->remove($contact);
        $em->flush();

        $this->addFlash('success', 'The contact has been removed');

        $response = new JsonResponse(array(
            'redirectTo'=>$this->get('router')->generate('farola_profile_contact')
        ));
        return $response;
    }

    /**
    *   @Route("/add-contact/{profileId}", name="farola_profile_add_contact_ajax")
    *   @Method({"POST"})
    */
    public function ajaxAddContactAction($profileId, Request $request){
        $em = $this->getDoctrine()->getManager();
        $profHelper = $this->get('farola_profile.helper');

        $profile = $profHelper->getCurrentUserProfile();

        $contact = $em->getRepository('FarolaProfileBundle:Relation')
            ->findOneBy(array(
                'relOwner' => $profile->getId(),
                'relatedProfile' => $profileId,
                'relationType'=> Relation::TYPE_CONTACT
            ));

        if (isset($contact) == false)
        {
            $newContact = new Relation($profile,$em->getRepository('FarolaProfileBundle:Profile')
                            ->findOneBy(array('id' => $profileId)), Relation::TYPE_CONTACT);
            $em->persist($newContact);
            $em->flush();
        }

        $response = new JsonResponse(array(
            'result'=>'ok'
        ));

        return $response;
    }
}
