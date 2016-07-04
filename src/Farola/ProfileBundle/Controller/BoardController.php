<?php

namespace Farola\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Farola\ProfileBundle\Form\ProfileSearchType;
use Farola\ProfileBundle\Entity\Review;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Farola\ProfileBundle\Entity\Relation;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class BoardController extends Controller
{
   /**
    *   @Route("/board/{namePart}", defaults={"namePart"=null}, name="farola_profile_board")
    */
   public function getBoardAction($namePart, Request $request) {

        $searchCrits = array();
        if (isset($namePart))
        {
            $searchCrits['name'] = $namePart;
        }

        $fnp = $this->getFormAndPaginator(0, $searchCrits, $request);

        return $this->render('FarolaProfileBundle:Board:Board.html.twig',
            array('searchForm' => $fnp['searchForm']->createView(),
                'pagProfiles' => $fnp['pagProfiles'],
                'qsDefault'=> array(
                    'search_type'=>'profile',
                     'data'=>array('code'=>$namePart,'name'=>$namePart)
                )
            )
        );
    }

    /**
    *   @Route("/board-more/{firstResult}", name="farola_profile_board_more_ajax")
    */
   public function ajaxMoreResultsAction($firstResult,Request $request) {
        $fnp = $this->getFormAndPaginator($firstResult, null,$request);

        $html = $this->renderView('FarolaProfileBundle:Board:content-list.html.twig',
            array('searchForm' => $fnp['searchForm']->createView(),
                'pagProfiles' => $fnp['pagProfiles'])
        );
        
        return new JsonResponse(array('html'=>$html));
   }

   public function getFormAndPaginator($firstResult, $defaultSearchCrits, $request) {
        $em = $this->get('doctrine.orm.entity_manager');


        $searchForm = $this->get('form.factory')->create(
            new ProfileSearchType,
            $defaultSearchCrits
        );
        
        $pagProfiles = [];
        
        if ($searchForm->handleRequest($request)->isValid()) 
        {
            $searchCrits = $searchForm->getData();

            $searchCrits['locationBB'] = $this->get('farola.calc.lon_lat')
                ->getBoundingBox(
                    $searchCrits['longitude'], 
                    $searchCrits['latitude'],
                    10);

            $pagProfiles = $em->getRepository('FarolaProfileBundle:Profile')
                ->getPaginatorSearch($searchCrits,$firstResult);
        }
        else 
        {
            $pagProfiles = $em->getRepository('FarolaProfileBundle:Profile')
                ->getPaginatorSearch($defaultSearchCrits,$firstResult);
        }

        return array('searchForm'=>$searchForm,'pagProfiles'=>$pagProfiles);
    }

}
