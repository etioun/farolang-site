<?php

namespace Farola\NoticeBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Farola\TestBundle\Tests\FarolaTestCase;

class BoardControllerTest extends FarolaTestCase
{
    public function testClickBoardDefault()
    {
        $this->clickAllLinks(FarolaTestCase::TEST_ACCOUNT_1, '/notice/board');
        $this->clickAllLinks(null, '/notice/board');
    }

    public function testClickBoardTeacher()
    {
        $this->clickAllLinks(FarolaTestCase::TEST_ACCOUNT_1, '/notice/board/teacher');
        $this->clickAllLinks(null, '/notice/board/teacher');
    }

    public function testClickBoardStudent()
    {
        $this->clickAllLinks(FarolaTestCase::TEST_ACCOUNT_1, '/notice/board/student');
        $this->clickAllLinks(null, '/notice/board/student');
    }

    public function testClickBoardLanguageExchange()
    {
        $this->clickAllLinks(FarolaTestCase::TEST_ACCOUNT_1, '/notice/board/practice');
        $this->clickAllLinks(null, '/notice/board/practice');
    }

    public function testClickBoardOther()
    {
        $this->clickAllLinks(FarolaTestCase::TEST_ACCOUNT_1, '/notice/board/other');
        $this->clickAllLinks(null, '/notice/board/other');
    }

    public function testClickBoardAll()
    {
        $this->clickAllLinks(FarolaTestCase::TEST_ACCOUNT_1, '/notice/board/all');
        $this->clickAllLinks(null, '/notice/board/all');
    }
}
