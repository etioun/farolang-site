<?php

namespace Farola\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Farola\TestBundle\Tests\FarolaTestCase;

class PreferenceControllerTest extends FarolaTestCase
{
    public function testPreference()
    {
        $this->clickAllLinks(FarolaTestCase::TEST_ACCOUNT_1, '/user/preference');
    }
}
