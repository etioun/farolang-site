<?php

namespace Farola\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    /**
     * @Route("/", name="farola_home")
     */
    public function homeAction(Request $request)
    {
    	if ($this->isGranted('ROLE_USER'))
        {
            $ph = $this->get('farola_profile.helper');
            $profile  = $ph->getCurrentUserProfile();

            $threads = $this->get('doctrine.orm.entity_manager')
                ->getRepository('FarolaMessageBundle:Thread')
                ->findByParticipant($profile,3);

            $notices = $this->get('doctrine.orm.entity_manager')
                ->getRepository('FarolaNoticeBundle:Notice')
                ->recentlyUpdated(10);

            return $this->render('FarolaHomeBundle::home.html.twig', 
                array('profile' => $profile, 'threads' =>$threads, 'notices'=>$notices)
            );    
        }
        else
        {
            return $this->render('FarolaHomeBundle::landing.html.twig');
        }

    }
}
