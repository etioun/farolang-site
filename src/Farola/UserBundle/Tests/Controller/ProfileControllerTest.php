<?php

namespace Farola\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Farola\TestBundle\Tests\FarolaTestCase;

class ProfileControllerTest extends FarolaTestCase
{
    public function testEdit()
    {
        $this->clickAllLinks(FarolaTestCase::TEST_ACCOUNT_1, '/user/identifier');
    }
}
