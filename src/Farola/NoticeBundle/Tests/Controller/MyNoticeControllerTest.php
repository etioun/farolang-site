<?php

namespace Farola\NoticeBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Farola\TestBundle\Tests\FarolaTestCase;

class MyNoticeControllerTest extends FarolaTestCase
{
    public function testClickList()
    {
        $this->clickAllLinks(FarolaTestCase::TEST_ACCOUNT_1, '/notice/my-list');
    }

    public function testClickNew()
    {
        $this->clickAllLinks(FarolaTestCase::TEST_ACCOUNT_1, '/notice/new');
        $this->clickAllLinks(FarolaTestCase::TEST_ACCOUNT_1, '/notice/new/teacher');
        $this->clickAllLinks(FarolaTestCase::TEST_ACCOUNT_1, '/notice/new/student');
        $this->clickAllLinks(FarolaTestCase::TEST_ACCOUNT_1, '/notice/new/language-partner');
        $this->clickAllLinks(FarolaTestCase::TEST_ACCOUNT_1, '/notice/new/other');

    }
}
