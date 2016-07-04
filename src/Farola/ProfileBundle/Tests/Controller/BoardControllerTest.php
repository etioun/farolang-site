<?php

namespace Farola\ProfileBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Farola\TestBundle\Tests\FarolaTestCase;

class BoardControllerTest extends FarolaTestCase
{
    public function testBoard()
    {
        $this->clickAllLinks(FarolaTestCase::TEST_ACCOUNT_1, '/profile/board');
    }
}
