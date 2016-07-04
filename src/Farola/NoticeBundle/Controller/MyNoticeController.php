<?php
namespace Farola\NoticeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Farola\NoticeBundle\Form\TeachingNoticeType;
use Farola\NoticeBundle\Form\NoticeType;

use Farola\NoticeBundle\Entity\Notice;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

class MyNoticeController extends Controller
{
    /**
    *   @Route("/my-list", name="farola_notice_my_list")
    */
    public function listAction(Request $request) {
        
        $ph = $this->get('farola_profile.helper');
        $nh = $this->get('farola_notice.helper');
        $profile = $ph->getCurrentUserProfile();

        $notices = $nh->getNotices($profile, false);

        return $this->render('FarolaNoticeBundle:My-notice:list.html.twig', array('notices'=>$notices));
    }

    /**
    *   @Route("/new/{category}", defaults={"category":null}, name="farola_notice_new")
    */
    public function newAction($category, Request $request)
    {
        $ph = $this->get('farola_profile.helper');
        $profile = $ph->getCurrentUserProfile();

        if ($category == 'teacher') {
            $categoryCode = Notice::CAT_TEACHING;
        }
        elseif ($category == 'student')
        {
            $categoryCode = Notice::CAT_STUDENT;
        }
        elseif ($category == 'language-partner')
        {
            $categoryCode = Notice::CAT_LANG_EX;
        }
        elseif ($category == 'other')
        {
            $categoryCode = Notice::CAT_OTHER;
        }
        else
        {
           return $this->render('FarolaNoticeBundle:My-notice:category_select.html.twig');
        }

        $notice = new Notice($profile, $categoryCode);
        
        return $this->editNotice($notice, $request);
    }

    /**
    *   @Route("/edit/{noticeId}", name="farola_notice_edit")
    */
    public function editAction($noticeId, Request $request)
    {
        $ph = $this->get('farola_profile.helper');
        $profile = $ph->getCurrentUserProfile();

        $notice = $this->getDoctrine()->getManager()->getRepository('FarolaNoticeBundle:Notice')
                                ->findOneBy(array('id' => $noticeId, 'profile' => $profile));

        return $this->editNotice($notice, $request);
    }

    protected function editNotice($notice, Request $request)
    {
        $ph = $this->get('farola_profile.helper');
        $profile = $ph->getCurrentUserProfile();

        if ($notice->getId() == null)
        {
           $categCount = $this->getDoctrine()->getManager()->getRepository('FarolaNoticeBundle:Notice')
            ->count($notice->getCategory(), $profile);

            if ($categCount >= 3)
            {
                $this->addFlash('error', 'Sorry you cannot have more than 3 notices of the same category');
                return $this->redirectToRoute('farola_notice_my_list');
            }
        }

        $form = $this->get('form.factory')->create(
            new NoticeType($this->container), 
            $notice,
            array('category'=>$notice->getCategory()));

        if ($request->isMethod('post')) {
            if ($form->handleRequest($request)->isValid()) 
            {
                $nmgr = $this->get('farola_notice.entity.notice_manager');

                if($nmgr->save($notice))
                {
                    return $this->redirectToRoute('farola_notice_show', array('noticeId'=>$notice->getId()));
                }
                else
                {
                    $request->addFlash('error', 'The notice could not be created');
                }
            }
            else
            {
                foreach ($form->getErrors() as $error) {
                        $this->addFlash('error', $error->getMessage());
                    }
            }
        }
        return $this->render('FarolaNoticeBundle:My-notice:edit.html.twig',
            array('form' => $form->createView()));
    }

    /**
    *   @Route("/ajax-activation/{noticeId}/{activate}", name="farola_notice_activation_ajax")
    *   @Method({"POST"})
    */
    public function ajaxActivationAction($noticeId, $activate, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $ph = $this->get('farola_profile.helper');
        $profile = $ph->getCurrentUserProfile();

        $notice = $em->getRepository('FarolaNoticeBundle:Notice')
           ->findOneBy(array('id' => $noticeId, 'profile' => $profile));

        $notice->setActivated($activate);
        $em->flush();

        $msg = ($activate == 0 ? 'Notice disabled' : 'Notice activated');

        $this->addFlash('sucess',$msg);
       
        return $this->redirectToRoute('farola_notice_my_list');
    }

    /**
    *   @Route("/ajax-delete/{noticeId}", name="farola_notice_delete_ajax")
    *   @Method({"POST"})
    */
    public function ajaxDeleteAction($noticeId, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $ph = $this->get('farola_profile.helper');
        $profile = $ph->getCurrentUserProfile();

        $notice = $em->getRepository('FarolaNoticeBundle:Notice')
           ->findOneBy(array('id' => $noticeId, 'profile' => $profile));

        if (is_object($notice))
        {
            $em->remove($notice);
            $em->flush();
            $em->clear();

            $this->addFlash('success','Notice deleted');
        }

        $response = new JsonResponse(array(
            'result'=>'ok'
        ));

        return $response;
    }
}
