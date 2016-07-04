<?php

namespace Farola\NoticeBundle\Entity;

use Symfony\Component\Intl\Intl;
use Farola\NoticeBundle\Entity\Notice;

class NoticeHelper
{
    private $em;

    private $securityContext;

    private $noticeCategories;

    public function __construct($entityManager, $securityContext){
        $this->em = $entityManager;
        $this->securityContext = $securityContext;
     }

    public function getNoticeCategories() {
        return json_decode(file_get_contents(__DIR__.'/../Resources/ref_data/notice-categories.json'), true);
    }

    public function isCategory($notice, $urlCodeGiven) {
        $noticeCategories = json_decode(file_get_contents(__DIR__.'/../Resources/ref_data/notice-categories.json'), true);

        foreach ($noticeCategories as $catCode => $catData) {
            if ($notice->getCategory() === intval($catCode))
            {
                if ($urlCodeGiven == $catData['url-code'])
                {
                   return true; 
                }
            }
        }
        return false;
    }

    public function getTags($scope) {
        $allTags = json_decode(file_get_contents(__DIR__.'/../Resources/ref_data/tags.json'), true);
        return $allTags[$scope];
    }

    public function getTagName($scope, $code) {
        $allTags = json_decode(file_get_contents(__DIR__.'/../Resources/ref_data/tags.json'), true);

        return $allTags[$scope][$code];
    }

    public function getTagNameByCategory($category, $code) {
        $allTags = json_decode(file_get_contents(__DIR__.'/../Resources/ref_data/tags.json'), true);

        return $allTags[$this->getTagScope($category)][$code];
    }

    public function getTagScope($category) {
        if ($category == Notice::CAT_TEACHING || $category == Notice::CAT_STUDENT) 
        {
            return 'teach-learn';
        }
        elseif ($category == Notice::CAT_OTHER)
        {
            return 'other';
        }
        return '';
    }

    public function getOnlineMethRef() {
        return json_decode(file_get_contents(__DIR__.'/../Resources/ref_data/online-methods.json'), true);
    }

    public function getOnlineMethName($code) {
        $allMeths = json_decode(file_get_contents(__DIR__.'/../Resources/ref_data/online-methods.json'), true);

        return $allMeths[$code];
    }

    public function getDaysOfWeek() {
        return json_decode(file_get_contents(__DIR__.'/../Resources/ref_data/days-week.json'), true);
    }

    public function getDayOfWeekName($dayOfWeekCode) {
        $daysOfWeek =  json_decode(file_get_contents(__DIR__.'/../Resources/ref_data/days-week.json'), true);
        
        return $daysOfWeek['names'][$dayOfWeekCode];
    }

    public function getPeriods() {
        return json_decode(file_get_contents(__DIR__.'/../Resources/ref_data/periods.json'), true);
    }

    public function getPeriodRange($periodCode) {
        $periods = json_decode(file_get_contents(__DIR__.'/../Resources/ref_data/periods.json'), true);
        // $ranges = $periods['ranges'];
        // dump($periodCode);
        return $periods['ranges'][$periodCode];
    }

    public function getHourStr($hour, $locale) {
        if ($locale = 'en') {
            if ($hour == 0)
                return '12am';
            if ($hour == 12)
                return '12pm';

            if ($hour > 12)
                return  ($hour-12).'pm';

            return $hour.'am';
        }
        return $hour;
    }

    public function getNotices($profile, $activeOnly = true)
    {
        if ($activeOnly)
        {
            return $this->em->getRepository('FarolaNoticeBundle:Notice')
            ->findBy(
                array('profile' => $profile, 'activated' => true), 
                array('category' => 'ASC'));
        }

        return $this->em->getRepository('FarolaNoticeBundle:Notice')
            ->findBy(
                array('profile' => $profile), 
                array('category' => 'ASC'));
    }

    // public function getLanguageName($code)
    // {
    //     return Intl::getLanguageBundle()->getLanguageName($code, null,'en');
    // }

    public function getAvailabilitiesView($notice, $dispTimezone = null){

        if ($notice->getAvailableAnytime()) {
            $result = [];
            for ($d = 0; $d < 7; $d++ ) {
                $result[] =array(
                    'day' => $d,
                    'periods'=> array(array('avail'=>true, 'span'=>24, 'day'=>$d, 'min'=>'', 'max'=>'')));
            }
            return $result;
        }

        $availabilities = $notice->getAvailabilities();

        if ($dispTimezone == null)
            $dispTimezone = $notice->getTimezone();

        $nTz = new \DateTimeZone($notice->getTimezone());
        $uTz = new \DateTimeZone($dispTimezone);

        $offset = $uTz->getOffset(new \DateTime("now", $uTz)) - $nTz->getOffset(new \DateTime("now", $nTz));
        $offset = $offset / 3600;
        $shiftedAvail = [];
        foreach ($availabilities as $availability) {
            $range = $this->getPeriodRange($availability['period']);
            
            $newMin = round($range[0] + $offset, 0, PHP_ROUND_HALF_DOWN);
            $newMax = round($range[1] + $offset, 0, PHP_ROUND_HALF_UP);
            $dayPos = $availability['dayOfWeek'];

            if ($newMin >= 0 && $newMax <= 24 ) {
                $shiftedAvail[$dayPos][$newMin][] = [$newMin, $newMax];
            }
            elseif ($newMin < 0 && $newMax <= 0 ) {
                $shiftedAvail[$this->getPreviousDayOfWeek($dayPos)][$newMin+24][] = [$newMin+24, $newMax+24];
            } 
            elseif ($newMin < 0 && $newMax > 0 ) {
                $shiftedAvail[$this->getPreviousDayOfWeek($dayPos)][$newMin+24][] = [$newMin+24, 24];
                $shiftedAvail[$dayPos][0][] = [0, $newMax];
            } 
            elseif ($newMin >= 24 && $newMax > 24 ) {
                $shiftedAvail[$this->getNextDayOfWeek($dayPos)][$newMin-24][] = [$newMin-24, $newMax-24];
            }
            elseif ($newMin < 24 && $newMax > 24 ) {
                $shiftedAvail[$this->getNextDayOfWeek($dayPos)][0][] = [0, $newMax-24];
                $shiftedAvail[$dayPos][$newMin][] = [$newMin, 24];
            }
        }

        $result = [];
        for ($d = 0; $d < 7; $d++ ) {
            $periods = [];
            
            $t = 0;
            while ($t<24) {
                    
                if (isset( $shiftedAvail[$d]) && 
                    isset( $shiftedAvail[$d][$t])) {
                    
                    $max = $shiftedAvail[$d][$t][0][1];
                    $min = $shiftedAvail[$d][$t][0][0];
                    $span =  $max - $min;
                    $periods[] = array('avail'=> true, 'span'=> $span, 'day'=>$d, 'min'=> $min, 'max'=> $max);
                    $t += $span;
                }
                else {
                    // if (count($periods) > 0 && end($periods)['avail'] == false) {
                    //         $clef = key($periods);
                    //         $periods[$clef]['span']++;
                    // }
                    // else {
                        $periods[] = array('avail'=> false, 'span'=> 1,'day'=>$d);
                    // }
                    $t++;
                }
            }
            $result[] =array('day' => $d, 'periods'=> $periods);
        } 
        return $result;
    }

    public function getNextDayOfWeek($dayOfWeek) {
        $plusUn = $dayOfWeek + 1;
        if ($plusUn > 6) {
            return 0;
        }
        return $plusUn;
    }

    public function getPreviousDayOfWeek($dayOfWeek) {
        $moinsUn = $dayOfWeek - 1;
        if ($moinsUn < 0 ) {
            return 6;
        }
        return $moinsUn;
    }


    public function isUserNotice($notice) {
        if (is_object($notice) == false)
            return false;

        return ($this->securityContext->getToken()->getUser() 
            == $notice->getProfile()->getUser());
    }
}
