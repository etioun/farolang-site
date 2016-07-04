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



class IgnoreController extends Controller
{
    /**
    *   @Route("/cancel-ignore/{profileId}", name="farola_profile_cancel_ignore_ajax")
    *   @Method({"POST"})
    */
    public function ajaxCancelIgnoreAction( $profileId, Request $request)
    {
        $ph = $this->get('farola_profile.helper');
        $em = $this->get('doctrine.orm.entity_manager');
        $relIgnore = $em->getRepository('FarolaProfileBundle:Relation')
            ->findOneBy(array(
                'relatedProfile'=>$profileId, 
                'relOwner'=> $ph->getCurrentUserProfile(), 
                'relationType'=> Relation::TYPE_IGNORE));

        if (isset($relIgnore))
        {
            $em->remove($relIgnore);
            $em->flush();
        }

    
        $response = new JsonResponse(array(
            'result'=>'ok'
        ));
        return $response;
    }

    /**
    *   @Route("/ignore/{profileId}", name="farola_profile_ignore_ajax")
    *   @Method({"POST"})
    */
    public function ajaxIgnoreAction($profileId, Request $request){
        $em = $this->getDoctrine()->getManager();
        $profHelper = $this->get('farola_profile.helper');

        $profile = $profHelper->getCurrentUserProfile();

        $ignoreRel = $em->getRepository('FarolaProfileBundle:Relation')
            ->findOneBy(array(
                'relOwner' => $profile->getId(),
                'relatedProfile' => $profileId,
                'relationType'=> Relation::TYPE_IGNORE
            ));

        $ignoredProfile = $em->getRepository('FarolaProfileBundle:Profile')
            ->findOneBy(array('id' => $profileId));

        if (isset($ignoreRel) == false)
        {
            $newIgnoreRel = new Relation($profile,$ignoredProfile, Relation::TYPE_IGNORE);
            $em->persist($newIgnoreRel);
            $em->flush();
            $this->addFlash('success', $ignoredProfile->getName().' is now ignored. You can cancel this action on '.$ignoredProfile->getName()."'s profile page.");

        }

        $response = new JsonResponse(array(
            'result'=>'ok'
        ));

        return $response;
    }
}
