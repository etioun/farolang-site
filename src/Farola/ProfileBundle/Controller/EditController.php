<?php

namespace Farola\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Farola\ProfileBundle\Form\GeneralType;
use Farola\ProfileBundle\Form\DescriptionType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Filesystem\Filesystem;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Farola\NoticeBundle\Entity\Notice;



class EditController extends Controller
{
    /**
    *   @Route("/edit/{tabId}", defaults={"tabId":"general"}, name="farola_profile_edit")
    */
    public function editAction($tabId, Request $request)
    {
       if ($tabId == 'general') 
       {
            return $this->editGeneral($request);
        }
        elseif ($tabId == 'picture')
        {
            return $this->editPicture($request);
        }
        elseif ($tabId == 'description')
        {
            return $this->editDescription($request);
        }
    }

    protected function editGeneral(Request $request) {

        $ph = $this->get('farola_profile.helper');
        $profile = $ph->getCurrentUserProfile();
        
        $form = $this->get('form.factory')
            ->create(new GeneralType($ph), $profile);

        if ($request->isMethod('post'))
        {
            if ($form->handleRequest($request)->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $langExNotices = $em->getRepository('FarolaNoticeBundle:Notice')->findBy(array(
                    'profile'=> $profile,
                    'category'=> Notice::CAT_LANG_EX));
                foreach ($langExNotices as $notice) {
                    $notice->setTeachedLanguage($ph->getSpokenLanguagesOver($profile, 3));
                }

                $em->flush();

                $this->addFlash('success', 'Your profile has been updated successfully');
                return $this->redirectToRoute('farola_profile_show', array('profileId'=> $profile->getId()));
            }
            else
            {
                $this->addFlash('error','The profile could not be updated');
            }
        }

        return $this->render('FarolaProfileBundle:Edit:general.html.twig',
            array('editForm' => $form->createView()));
    }

    protected function editPicture(Request $request) {

        $ph = $this->get('farola_profile.helper');
        $profile = $ph->getCurrentUserProfile();

        if ($request->isMethod('post'))
        {
            $this->uploadPicture($request);
            return $this->redirectToRoute('farola_profile_show', array('profileId'=> $profile->getId()));
        }

        
        
        $picFn = $profile->getProfilePictureFilename();

        return $this->render('FarolaProfileBundle:Edit:picture.html.twig', 
            array('picUrl' =>  $ph->getProfilePictureDirectoryUrl().'/'.$picFn));
    }

    protected function uploadPicture($request) {
        $data = $request->request->get("picData");
        
        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);
        $data = base64_decode($data);

        $profHelper = $this->get('farola_profile.helper');
        
        $profile = $profHelper->getCurrentUserProfile();
        
        if($profHelper->saveProfilePicture($data, $profile))
        {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'My god ! You look so great on that picture !');
        }
        else
        {
            $this->addFlash('The picture could not be uploaded');
        }
    }

    /**
    *   @Route("/remove-pic", name="farola_profile_remove_pic")
    */
    public function removePictureAction(Request $request)
    {
        $profHelper = $this->get('farola_profile.helper');
        
        $profile = $profHelper->getCurrentUserProfile();

        $profHelper->removeProfilePicture($profile);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('farola_profile_edit', array('tabId'=>'picture'));
    }

    protected function editDescription(Request $request) {

        $ph = $this->get('farola_profile.helper');
        $profile = $ph->getCurrentUserProfile();
        
        $form = $this->get('form.factory')
            ->create(new DescriptionType($ph), $profile);
        
        if ($request->isMethod('post'))
        {
            if ($form->handleRequest($request)->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash('success', 'Your profile has been updated successfully');
                return $this->redirectToRoute(
                    'farola_profile_show', array('profileId'=> $profile->getId()));
            }
            else
            {
                $this->addFlash('error','The profile could not be updated');
            }
        }

        return $this->render('FarolaProfileBundle:Edit:description.html.twig',
            array('editForm' => $form->createView()));
    }
}
