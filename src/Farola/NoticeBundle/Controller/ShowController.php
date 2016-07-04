<?php
namespace Farola\NoticeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Farola\NoticeBundle\Form\TeachingNoticeType;
use Farola\NoticeBundle\Form\NoticeType;

use Farola\NoticeBundle\Entity\Notice;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ShowController extends Controller
{
    
    /**
    *   @Route("/show-wl/{noticeId}/{showGoBack}", defaults={"showGoBack"="0"}, name="farola_notice_show_with_login")
    */
    public function showWithLoginAction($noticeId, $showGoBack, Request $request)
    {
        return $this->showAction($noticeId, $showGoBack, $request);
    }

    /**
    *   @Route("/show/{noticeId}/{showGoBack}", defaults={"showGoBack"="0"}, name="farola_notice_show")
    */
    public function showAction($noticeId, $showGoBack, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $ph = $this->get('farola_profile.helper');
        $nh = $this->get('farola_notice.helper');

        $notice = $em->getRepository('FarolaNoticeBundle:Notice')
           ->findOneBy(array('id' => $noticeId));

        if (is_object($notice) == false
            || ($notice->getActivated() == false && $nh->isUserNotice($notice) == false)
            )
        {
            $this->addFlash('error', 'Sorry the notice has been deleted or disabled');
        }

        return $this->render('FarolaNoticeBundle:Show:show.html.twig',
            array('notice' => $notice, 'showGoBack' => $showGoBack));
    }
}
