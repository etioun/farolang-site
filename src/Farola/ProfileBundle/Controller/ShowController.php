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


class ShowController extends Controller
{
    /**
    *   @Route("/show/{profileId}/{tabId}/{showGoBack}", defaults={"tabId"="description","showGoBack"="0"},name="farola_profile_show")
    */
    public function showAction($profileId, $tabId, $showGoBack, Request $request)
    {
       $profHelper = $this->get('farola_profile.helper');
       $nh = $this->get('farola_notice.helper');
       $em = $this->getDoctrine()->getManager();

       $userProfile = $profHelper->getCurrentUserProfile();
       $visitedProfile = $em->getRepository('FarolaProfileBundle:Profile')
                            ->findOneBy(array('id' => $profileId));

        // if ($request->isMethod('post'))
        // {
        //     $reviewType = new ReviewType;

        //     if ($request->request->has($reviewType->getName())
        //         && $profHelper->hasReviewed($userProfile, $visitedProfile) === false) {

        //         $review = new Review;
        //         $review->setWriter($userProfile);
        //         $review->setSubject($visitedProfile);

        //         $reviewFormFilled = $this->get('form.factory')->create($reviewType, $review);

        //         if($reviewFormFilled->handleRequest($request)->isValid()) {
        //             $em = $this->getDoctrine()->getManager();

        //             $backReview = $em->getRepository('FarolaProfileBundle:Review')
        //                 ->findOneBy(array('writer'=>$visitedProfile, 'subject'=>$userProfile));

        //             if (is_object($backReview)) {
        //                $review->setBackReview($backReview);
        //                $backReview->setBackReview($review);
        //             }

        //             $em->persist($review);
        //             $em->flush();
        //             $visitedProfile->setAggReviewCount($em->getRepository('FarolaProfileBundle:Review')
        //                     ->countFor($visitedProfile));
        //             $em->flush();

        //             $request->getSession()->getFlashBag()->add('success',"Review posted");
        //         }
        //         else
        //         {
        //             foreach ($form->getErrors() as $error)
        //                 $request->getSession()->getFlashBag()->add('error',$error->getMessage());
        //         }
        //     }
        // }

        $pagReviews = $em->getRepository('FarolaProfileBundle:Review')
            ->getPaginatorFindBySubject($profileId,0);

        $reviewFormEmpty = $this->get('form.factory')->create(new ReviewType, null);

        return $this->render('FarolaProfileBundle:Show:show.html.twig',array(
            'profile' => $visitedProfile,
            'visitor' => $userProfile,
            'pagReviews' => $pagReviews,
            'reviewForm' => $reviewFormEmpty->createView(),
            'hasReviewed' => $profHelper->hasReviewed($userProfile,$visitedProfile),
            'tabId' => $tabId, 
            'showGoBack'=>$showGoBack,
            ));
    }

    /**
    *   @Route("/put-review/{profileId}", name="farola_profile_put_review_ajax")
    *   @Method({"POST"})
    */
    public function ajaxPutReviewAction($profileId, Request $request){
        $em = $this->getDoctrine()->getManager();
        $profHelper = $this->get('farola_profile.helper');

        $userProfile = $profHelper->getCurrentUserProfile();
        $visitedProfile = $em->getRepository('FarolaProfileBundle:Profile')
                            ->findOneBy(array('id' => $profileId));

        if ($profHelper->hasReviewed($userProfile, $visitedProfile) == false) {

            $review = new Review;
            $review->setWriter($userProfile);
            $review->setSubject($visitedProfile);

            $reviewFormFilled = $this->get('form.factory')->create(new ReviewType, $review);

            if($reviewFormFilled->handleRequest($request)->isValid()) {
                $backReview = $em->getRepository('FarolaProfileBundle:Review')
                    ->findOneBy(array('writer'=>$visitedProfile, 'subject'=>$userProfile));

                if (is_object($backReview)) {
                   $review->setBackReview($backReview);
                   $backReview->setBackReview($review);
                }

                $em->persist($review);
                $em->flush();
                $visitedProfile->setAggReviewCount($em->getRepository('FarolaProfileBundle:Review')
                        ->countFor($visitedProfile));
                $em->flush();

                $request->getSession()->getFlashBag()->add('success',"Your review has been posted successfully");
            }
            else
            {
                foreach ($form->getErrors() as $error)
                    $request->getSession()->getFlashBag()->add('error',$error->getMessage());
            }
        }

        $response = new JsonResponse(array(
            'result'=>'ok'));

        return $response;
    }

    /**
    *   @Route("/delete-review/{reviewId}", name="farola_profile_delete_review_ajax")
    *   @Method({"POST"})
    */
    public function ajaxDeleteReviewAction( $reviewId, Request $request){
        $em = $this->getDoctrine()->getManager();
        $profHelper = $this->get('farola_profile.helper');

        $profile = $profHelper->getCurrentUserProfile();

        $review = $em->getRepository('FarolaProfileBundle:Review')
            ->findOneBy(array('writer' => $profile, 'id' =>$reviewId));

        if (is_object($review))
        {
            $subjectId = $review->getSubject()->getId();
            $em->remove($review);
            $em->flush();
            $em->clear();

            $subject = $em->getRepository('FarolaProfileBundle:Profile')
                ->findOneById($subjectId);
            $subject->setAggReviewCount($em->getRepository('FarolaProfileBundle:Review')
                ->countFor($subject));

            $em->flush();

        }

        $this->addFlash('success', 'The review has been removed');

        $response = new JsonResponse(array(
            'result'=>'ok'
        ));

        return $response;
    }

    /**
    *   @Route("/review-more/{profileId}/{firstResult}", name="farola_profile_show_review_more_ajax")
    */
   public function ajaxMoreReviewsAction($profileId, $firstResult,Request $request) {
        $em = $this->getDoctrine()->getManager();
        $profHelper = $this->get('farola_profile.helper');
        $pagReviews = $em->getRepository('FarolaProfileBundle:Review')
            ->getPaginatorFindBySubject($profileId,$firstResult);
        $visitedProfile = $em->getRepository('FarolaProfileBundle:Profile')
                            ->findOneBy(array('id' => $profileId));

        $html = $this->renderView('FarolaProfileBundle:Show:content-review-list.html.twig',
            array('visitor' => $profHelper->getCurrentUserProfile(),
                'pagReviews' => $pagReviews,
                'profile' => $visitedProfile)
        );

        return new JsonResponse(array('html'=>$html));
   }

}
