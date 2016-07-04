<?php

namespace Farola\MessageBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Farola\TestBundle\Tests\FarolaTestCase;

class MessageControllerTest extends FarolaTestCase
{
    public function testClicksBoardGeneral()
    {
        $this->clickAllLinks(FarolaTestCase::TEST_ACCOUNT_1, '/message/board');
        $this->clickAllLinks(FarolaTestCase::TEST_ACCOUNT_1, '/message/board/general');
    }

    public function testClicksBoardNotice()
    {
        $this->clickAllLinks(FarolaTestCase::TEST_ACCOUNT_1, '/message/board/notice');
    }
}
