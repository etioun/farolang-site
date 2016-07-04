<?php

namespace Farola\MainBundle\Calc;

class LonLatCalc 
{
    const EARTH_RADIUS = 6371;

    public function getBoundingBox($lon, $lat, $radius) {
        if (isset($lon) == false || isset($lat) == false) {
            return null;
        }

        // first-cut bounding box (in degrees)
        $maxLat = $lat + rad2deg($radius/LonLatCalc::EARTH_RADIUS);
        $minLat = $lat - rad2deg($radius/LonLatCalc::EARTH_RADIUS);
        // compensate for degrees longitude getting smaller with increasing latitude
        $maxLon = $lon + rad2deg($radius/LonLatCalc::EARTH_RADIUS/cos(deg2rad($lat)));
        $minLon = $lon - rad2deg($radius/LonLatCalc::EARTH_RADIUS/cos(deg2rad($lat)));

        return new LonLatBBox($minLat, $maxLat, $minLon, $maxLon);
    }
}
