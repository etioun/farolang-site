<?php
namespace Farola\NoticeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Farola\NoticeBundle\Form\NoticeSearchType;
use Farola\NoticeBundle\Entity\Notice;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

class BoardController extends Controller
{
    private $qsDefault = null;

    /**
    *   @Route("/board/search", name="farola_notice_board_search")
    */
    public function getSearchBoardAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $searchCrits = array('categories'=>array(Notice::CAT_STUDENT, Notice::CAT_OTHER, Notice::CAT_TEACHING, Notice::CAT_LANG_EX));
        $searchType = "all";

        if (isset($language))
        {
            $searchCrits['involvedLanguage'] = $language;
            $rdh = $this->get('farola.ref_data.helper');
            $this->qsDefault = array(
                'search_type'=>$searchType, 
                'data'=>array('code'=>$language,'name'=>$rdh->getLanguageName($language))
            );
        }
        else
        {
           $this->qsDefault = array(
                'search_type'=>$searchType, 
                'data'=>null
            ); 
        }



        $searchForm = $this->getForm($searchType, $searchCrits);

        return $this->render('FarolaNoticeBundle:Board:search_only_board.html.twig',
            array('searchForm' => $searchForm->createView(),
                'searchType' => $searchType,
                'qsDefault'=>$this->qsDefault)
        );
    }

    /**
    *   @Route("/board/all/{language}", defaults={"language"=null},name="farola_notice_board_all")
    */
    public function getAllBoardAction($language, Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $searchCrits = array('categories'=>array(Notice::CAT_STUDENT, Notice::CAT_OTHER, Notice::CAT_TEACHING, Notice::CAT_LANG_EX));
        if (isset($language))
        {
            $searchCrits['involvedLanguage'] = $language;
            $rdh = $this->get('farola.ref_data.helper');
            $this->qsDefault = array(
                'search_type'=>'all', 
                'data'=>array('code'=>$language,'name'=>$rdh->getLanguageName($language))
            );
        }
        else
        {
           $this->qsDefault = array(
                'search_type'=>'all', 
                'data'=>null
            ); 
        }

        return $this->getSearchNotice('all', $searchCrits, $request);
    }

    /**
    *   @Route("/board/learn/{language}", defaults={"language"=null},name="farola_notice_board_learn")
    */
    public function getLearnBoardAction($language, Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $searchCrits = array('categories'=>array(Notice::CAT_TEACHING, Notice::CAT_LANG_EX));
        $searchType = "all";
        if (isset($language))
        {
            $searchCrits['teachedLanguage'] = $language;
            $rdh = $this->get('farola.ref_data.helper');
            $this->qsDefault = array(
                'search_type'=>'all', 
                'data'=>array('code'=>$language,'name'=>$rdh->getLanguageName($language))
            );
        }
        else
        {
           $this->qsDefault = array(
                'search_type'=>'all', 
                'data'=>null
            ); 
        }

        $searchForm = $this->getForm($searchType, $searchCrits);

        return $this->render('FarolaNoticeBundle:Board:search_only_board.html.twig',
            array('searchForm' => $searchForm->createView(),
                'searchType' => $searchType,
                'qsDefault'=>$this->qsDefault));
    }

    /**
    *   @Route("/board", name="farola_notice_board")
    */
    public function getDefaultBoardAction(Request $request)
    {
        return $this->getLearnBoardAction(null, $request);
    }

    /**
    *   @Route("/board/teach/{language}", defaults={"language"=null},name="farola_notice_board_teach")
    */
    public function getTeachBoardAction($language, Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $searchCrits = array('categories'=>array(Notice::CAT_STUDENT, Notice::CAT_LANG_EX));
        $searchType = "all";
        if (isset($language))
        {
            $searchCrits['learnedLanguage'] = $language;
            $rdh = $this->get('farola.ref_data.helper');
            $this->qsDefault = array(
                'search_type'=>'all', 
                'data'=>array('code'=>$language,'name'=>$rdh->getLanguageName($language))
            );
        }
         else
        {
           $this->qsDefault = array(
                'search_type'=>'all', 
                'data'=>null
            ); 
        }

        $searchForm = $this->getForm($searchType, $searchCrits);

        return $this->render('FarolaNoticeBundle:Board:search_only_board.html.twig',
            array('searchForm' => $searchForm->createView(),
                'searchType' => $searchType,
                'qsDefault'=>$this->qsDefault));
    }

    /**
    *   @Route("/board/teacher", name="farola_notice_board_teacher")
    */
    public function getTeachersBoardAction(Request $request)
    {
        $searchCrits = array('category'=>Notice::CAT_TEACHING);
        return $this->getSearchNotice('teacher_notice',$searchCrits, $request);
    }

    /**
    *   @Route("/board/student", name="farola_notice_board_student")
    */
    public function getStudentsBoardAction(Request $request)
    {
        $searchCrits = array('category'=>Notice::CAT_STUDENT);
        return $this->getSearchNotice('student_notice', $searchCrits,$request);
    }

    /**
    *   @Route("/board/practice/{language}", defaults={"language"=null}, name="farola_notice_board_lang_ex")
    */
    public function getLangExBoardAction($language,Request $request)
    {
       $searchCrits = array('categories'=>array(Notice::CAT_LANG_EX));
       $searchType = "all";
       if (isset($language))
       {
            $searchCrits['teachedLanguage'] = $language;
            $rdh = $this->get('farola.ref_data.helper');
            $this->qsDefault = array(
                'search_type'=>'all', 
                'data'=>array('code'=>$language,'name'=>$rdh->getLanguageName($language))
            );
        }
        else
        {
           $this->qsDefault = array(
                'search_type'=>'all', 
                'data'=>null
            );
        }
 
        $searchForm = $this->getForm($searchType, $searchCrits);

        return $this->render('FarolaNoticeBundle:Board:search_only_board.html.twig',
            array('searchForm' => $searchForm->createView(),
                'searchType' => $searchType,
                'qsDefault'=>$this->qsDefault));
    }

    /**
    *   @Route("/board/other/{language}", defaults={"language"=null}, name="farola_notice_board_other")
    */
    public function getOtherAction($language,Request $request)
    {
        $searchCrits = array('category'=>Notice::CAT_OTHER);
        if (isset($language))
        {
            $searchCrits['teachedLanguage'] = $language;
            $rdh = $this->get('farola.ref_data.helper');
            $this->qsDefault = array(
                'search_type'=>'other', 
                'data'=>array('code'=>$language,'name'=>$rdh->getLanguageName($language))
            );
        }
         else
        {
           $this->qsDefault = array(
                'search_type'=>'other', 
                'data'=>null
            ); 
        }

        return $this->getSearchNotice('other_notice', $searchCrits, $request);
    }

    protected function getSearchNotice($searchType, $defaultSearchCrits, Request $request) {
        if (isset($defaultSearchCrits) == false)
        {
            $defaultSearchCrits = array();
        }

        $fpnc = $this->getFormAndPaginator($searchType, 0, $defaultSearchCrits, $request);

        return $this->render('FarolaNoticeBundle:Board:board.html.twig',
            array('searchForm' => $fpnc['searchForm']->createView(),
                'pagNotices' => $fpnc['pagNotices'],
                'searchType' => $searchType,
                'qsDefault'=>$this->qsDefault)
        );
    }

    /**
    *   @Route("/board-more/{searchType}/{firstResult}", name="farola_notice_board_more_ajax")
    */
   public function ajaxMoreResultsAction($searchType, $firstResult,Request $request) {
        $fpnc = $this->getFormAndPaginator($searchType,$firstResult, $request);

        $html = $this->renderView('FarolaNoticeBundle:Board:content-list.html.twig',
            array('searchForm' => $fpnc['searchForm']->createView(),
                'pagNotices' => $fpnc['pagNotices'],
                'searchType' => $searchType)
        );
        
        return new JsonResponse(array('html'=>$html));
   }

   public function getForm($searchType, $defaultSearchCrits)
   {
        $searchForm = $this->get('form.factory')->create(
            new NoticeSearchType($this->container), 
            $defaultSearchCrits,
            array('search_type' => $searchType)
            );

        return $searchForm;
   }

   public function getFormAndPaginator($searchType, $firstResult, $defaultSearchCrits, $request) {
        $em = $this->get('doctrine.orm.entity_manager');

        $searchForm = $this->getForm($searchType,$defaultSearchCrits);

        if ($searchForm->handleRequest($request)->isValid()) {
            $searchCrits = $searchForm->getData();

            if ($searchCrits['onlineService'] == false)
            {
                $searchCrits['locationBB'] = $this->get('farola.calc.lon_lat')
                    ->getBoundingBox(
                        $searchCrits['longitude'], 
                        $searchCrits['latitude'],
                        10);
                unset($searchCrits['profile_country']);
                unset($searchCrits['onlineService']);
            }
            else
            {
                unset($searchCrits['locationBB']);
            }

            $pagNotices = $em->getRepository('FarolaNoticeBundle:Notice')
                ->getPaginatorSearch($searchCrits, $firstResult);
        }
       else 
       {
            $pagNotices = $em->getRepository('FarolaNoticeBundle:Notice')
                ->getPaginatorSearch($defaultSearchCrits,$firstResult);
        }

        return array('searchForm'=>$searchForm,'pagNotices'=>$pagNotices);
    }
    
}
