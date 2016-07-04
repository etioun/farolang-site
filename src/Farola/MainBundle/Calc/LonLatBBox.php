<?php

namespace Farola\MainBundle\Calc;

class LonLatBBox
{
    public $maxLat;

    public $minLat;

    public $maxLon;

    public $minLon;

    public function __construct($minLat, $maxLat, $minLon,$maxLon) {
    	$this->minLat = $minLat;
    	$this->maxLat = $maxLat;
    	$this->minLon = $minLon;
    	$this->maxLon = $maxLon;
    }
}
