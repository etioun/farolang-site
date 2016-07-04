<?php

namespace Farola\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Farola\TestBundle\Tests\FarolaTestCase;

class ChangePasswordControllerTest extends FarolaTestCase
{
    public function testChangePassword()
    {
        $this->clickAllLinks(FarolaTestCase::TEST_ACCOUNT_1, '/user/change-password');
    }
}
