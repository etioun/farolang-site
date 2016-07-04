<?php

namespace Farola\ProfileBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Farola\TestBundle\Tests\FarolaTestCase;

class EditControllerTest extends FarolaTestCase
{
    public function testEdit()
    {
        $this->clickAllLinks(FarolaTestCase::TEST_ACCOUNT_1, '/profile/edit');
        $this->clickAllLinks(FarolaTestCase::TEST_ACCOUNT_1, '/profile/edit/general');
        $this->clickAllLinks(FarolaTestCase::TEST_ACCOUNT_1, '/profile/edit/picture');
        $this->clickAllLinks(FarolaTestCase::TEST_ACCOUNT_1, '/profile/edit/description');
    }
}
