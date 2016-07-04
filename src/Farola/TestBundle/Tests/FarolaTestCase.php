<?php

namespace Farola\TestBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseTest;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;

abstract class FarolaTestCase extends BaseTest
{
    static $checkedUrl = array();

    const TEST_ACCOUNT_1 = "ecaxard@gmail.com";

    protected function clickAllLinks($usermail, $address)
    {
        $client = $this->createAuthorizedClient($usermail);

        $crawler = $client->request('GET', $address);
        $this->assertTrue($client->getResponse()->isClientError() == false, 'problem with '.$address);
        $this->assertTrue($client->getResponse()->isServerError() == false,'problem with '.$address);

        $links = $crawler->filter('a')->links();


        foreach ($links as $link) {
         	if (($link->getUri() == 'javascript:void(0)')
                || strpos($link->getUri(), "mailto:") == 0
                || in_array($link->getUri(), FarolaTestCase::$checkedUrl))
                continue;

            $client->click($link);
         	$this->assertTrue($client->getResponse()->isClientError() == false,  $this->failureMessage($link, $client->getResponse()));
        	$this->assertTrue($client->getResponse()->isServerError() == false,$this->failureMessage($link, $client->getResponse()));

        	if ($client->getResponse() instanceof RedirectResponse){
        		$client->followRedirect();
        		$this->assertTrue($client->getResponse()->isClientError() == false,  $this->failureMessage($link, $client->getResponse()));
        		$this->assertTrue($client->getResponse()->isServerError() == false,$this->failureMessage($link, $client->getResponse()));
        	}

            if(strstr( $link->getUri(),'logout'))
            {
                $client = $this->createAuthorizedClient($usermail);
            }

            FarolaTestCase::$checkedUrl[] = $usermail.$link->getUri();
        }

    }

    // protected function checkLinksRecursive($client, $crawler) {
    //     $links = $crawler->filter('a')->links();

    //     foreach ($links as $link) {
    //         if (($link->getUri() == 'javascript:void(0)')
    //             || in_array($link->getUri(), FarolaTestCase::$checkedUrl))
    //             continue;

    //         $crawlerNew = $client->click($link);
    //         $this->assertTrue($client->getResponse()->isClientError() == false,  $this->failureMessage($link, $client->getResponse()));
    //         $this->assertTrue($client->getResponse()->isServerError() == false,$this->failureMessage($link, $client->getResponse()));

    //         if ($client->getResponse() instanceof RedirectResponse){
    //             $crawlerNew = $client->followRedirect();
    //             $this->assertTrue($client->getResponse()->isClientError() == false,  $this->failureMessage($link, $client->getResponse()));
    //             $this->assertTrue($client->getResponse()->isServerError() == false,$this->failureMessage($link, $client->getResponse()));
    //         }

    //         if(strstr( $link->getUri(),'logout'))
    //         {
    //             $client = $this->createAuthorizedClient($usermail);
    //         }

    //         FarolaTestCase::$checkedUrl[] = $link->getUri();
            
    //         $this->checkLinksRecursive($client, $crawlerNew);
    //     }
    // }

    protected function failureMessage($link, $response) {
    	$msg='';

    	$msg.= ' reponse content : '.$response->getContent();
    	if (isset($link->getNode()->firstChild) && isset($link->getNode()->firstChild->data))
    	{
    		$msg.= 'Link label : '.$link->getNode()->firstChild->data;
    	}
    	$msg.= ' Link uri : '.$link->getUri();
		return $msg;
    }

    protected function createAuthorizedClient($usermail)
	{
	 $client = static::createClient();
	 if(isset($usermail))
     {

         $container = $client->getContainer();

    	 $session = $container->get('session');
    	 /** @var $userManager \FOS\UserBundle\Doctrine\UserManager */
    	 $userManager = $container->get('fos_user.user_manager');
    	 /** @var $loginManager \FOS\UserBundle\Security\LoginManager */
    	 $loginManager = $container->get('fos_user.security.login_manager');
    	 $firewallName = $container->getParameter('fos_user.firewall_name');

    	 $user = $userManager->findUserBy(array('email' => $usermail));
    	 $loginManager->loginUser($firewallName, $user);

    	 // save the login token into the session and put it in a cookie
    	 $container->get('session')->set('_security_' . $firewallName, 
    	 serialize($container->get('security.context')->getToken()));
    	 $container->get('session')->save();
    	 $client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));

         $this->assertTrue($client->getContainer()->get('security.context')->isGranted('ROLE_USER'));
     }

	 return $client;
	}
}
