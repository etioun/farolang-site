<?php

namespace Farola\HomeBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Farola\TestBundle\Tests\FarolaTestCase;

class DashboardControllerTest extends FarolaTestCase
{
    public function testClicksHome()
    {
        $this->clickAllLinks(FarolaTestCase::TEST_ACCOUNT_1, '/');
        $this->clickAllLinks(null, '/');
    }
}
