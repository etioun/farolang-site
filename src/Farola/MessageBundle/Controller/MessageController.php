<?php
namespace Farola\MessageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Farola\MessageBundle\Entity\Thread;
use Symfony\Component\HttpFoundation\JsonResponse;


class MessageController extends Controller
{
    /**
    *   @Route("/board/{category}", defaults={"category"="general"}, name="farola_message_board")
    */
    public function boardAction($category, Request $request)
    {
        if($category == 'notice')
        {
            $techCategory = Thread::CAT_NOTICE;
        }
        else
        {
            $techCategory = Thread::CAT_GENERAL;
        }

        return $this->renderBoard($techCategory);
    }

    protected function renderBoard($category) {
        $ph = $this->get('farola_profile.helper');
        $usrProfile = $ph->getCurrentUserProfile();

        $pagThreads = $this->get('doctrine.orm.entity_manager')
            ->getRepository('FarolaMessageBundle:Thread')
            ->getPaginatorByParticipantAndCategory($usrProfile, $category,0);

        $currentThread = $pagThreads->getIterator()->current();
        
        $pagMessages = null;
        if (is_object($currentThread))
        {
            $pagMessages = $this->get('doctrine.orm.entity_manager')
                ->getRepository('FarolaMessageBundle:Message')
                ->getPaginatorByThreadAndParticipant($currentThread, $usrProfile, 0);

            $mh = $this->get('farola_message.helper');
            $mh->markAsRead($currentThread,$usrProfile);
        }

        if($category == Thread::CAT_NOTICE)
        {
            $dispCategory = 'notice';
        }
        else
        {
            $dispCategory = 'general';
        }
        
        return $this->render('FarolaMessageBundle:Board:board.html.twig', 
            array('pagThreads' => $pagThreads,
                'usrProfile' => $usrProfile,
                'currentThread' => $currentThread,
                'pagMessages' => $pagMessages,
                'category' => $dispCategory,
                'mh'=> $this->get('farola_message.helper')
            )
        );
    }
    
    /**
    *   @Route("/message-list/{threadId}/{firstResult}", defaults={"firstResult"=0}, name="farola_message_list_ajax")
    */
    public function ajaxThreadMessageListAction($threadId,$firstResult, Request $request)
    {
        $ph = $this->get('farola_profile.helper');
        $mh = $this->get('farola_message.helper');
        $usrProfile = $ph->getCurrentUserProfile();

        $pagMessages = $this->get('doctrine.orm.entity_manager')
            ->getRepository('FarolaMessageBundle:Message')
            ->getPaginatorByThreadAndParticipant($threadId, $usrProfile, $firstResult);

        $currentThread = $this->get('doctrine.orm.entity_manager')
            ->getRepository('FarolaMessageBundle:Thread')
            ->findOneById($threadId);

        $mh->markAsRead($currentThread,$usrProfile);

        $html = $this->renderView('FarolaMessageBundle:Board:content-message-list.html.twig',
            array(
                'pagMessages' => $pagMessages, 
                'currentThread' => $currentThread,
                'mh'=> $this->get('farola_message.helper'),
                'usrProfile' => $usrProfile
            )
            );
        return new JsonResponse(array('html'=>$html)); 
    }

    /**
    *   @Route("/thread-more/{category}/{firstResult}", name="farola_message_thread_more_ajax")
    */
    public function ajaxMoreThreadAction($category,$firstResult, Request $request)
    {
        $ph = $this->get('farola_profile.helper');
        $usrProfile = $ph->getCurrentUserProfile();

        if($category == 'notice')
        {
            $techCategory = Thread::CAT_NOTICE;
        }
        else
        {
            $techCategory = Thread::CAT_GENERAL;
        }

        $pagThreads = $this->get('doctrine.orm.entity_manager')
            ->getRepository('FarolaMessageBundle:Thread')
            ->getPaginatorByParticipantAndCategory($usrProfile,$techCategory, $firstResult);

        $html = $this->renderView('FarolaMessageBundle:Board:content-thread-list.html.twig',
            array(
                'pagThreads' => $pagThreads,
                'category'=>$category,
                'usrProfile'=>$usrProfile
                )
        );
        return new JsonResponse(array('html'=>$html)); 
    }

    
    /**
    *   @Route("/notice-message-put/{noticeId}", name="farola_message_notice_put_ajax")
    *   @Method({"POST"})
    */
    public function ajaxMsgNoticeAction( $noticeId, Request $request)
    {
        $ph = $this->get('farola_profile.helper');
        $mh = $this->get('farola_message.helper');
        $usrProfile = $ph->getCurrentUserProfile(); 

        $message = $request->request->get('sendMsgFrm')['msgTxt'];

        if (isset($message) && isset($noticeId)) {
            $mh->sendNoticeMessage($message, $usrProfile, $noticeId);
            $this->addFlash('success', 'The message has been sent');
        }

        return new JsonResponse(array('result'=>'ok'));

    }

    /**
    *   @Route("/thread-message-put/{threadId}", name="farola_message_thread_message_put_ajax")
    *   @Method({"POST"})
    */
    public function ajaxThreadMsgPutAction( $threadId, Request $request)
    {
        $ph = $this->get('farola_profile.helper');
        $mh = $this->get('farola_message.helper');
        $usrProfile = $ph->getCurrentUserProfile(); 

        $message = $request->request->get('sendMsgFrm')['msgTxt'];
        if (isset($message)) {
            $this->get('farola_message.helper')
                ->sendMessageWithThreadId($message, $usrProfile,$threadId);
            $this->addFlash('success', 'The message has been sent');
        }

        return new JsonResponse(array('result'=>'ok'));

    }

    /**
    *   @Route("/profile-message-put/{profileId}", name="farola_message_profile_message_put_ajax")
    *   @Method({"POST"})
    */
    public function ajaxProfileMsgPutAction( $profileId, Request $request)
    {
        $ph = $this->get('farola_profile.helper');
        $mh = $this->get('farola_message.helper');
        $em = $this->getDoctrine()->getManager();

        $usrProfile = $ph->getCurrentUserProfile(); 
        $visitedProfile = $em->getRepository('FarolaProfileBundle:Profile')
                            ->findOneBy(array('id' => $profileId));

        $message = $request->request->get('sendMsgFrm')['msgTxt'];
        if (isset($message)) {
            $this->get('farola_message.helper')
                ->sendGeneralMessage($message, $usrProfile, $visitedProfile);
            $this->addFlash('success', 'The message has been sent');
        }

        return new JsonResponse(array('result'=>'ok'));
    }
}
