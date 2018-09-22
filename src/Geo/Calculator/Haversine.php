<?php

declare(strict_types=1);

namespace Taavit\Trackee\Geo\Calculator;

use Taavit\Trackee\Geo\Unit\GeoPoint;
use Taavit\Trackee\Unit\Distance;

use function atan2;
use function cos;
use function deg2rad;
use function sin;
use function sqrt;

class Haversine implements Calculator
{
    public function __invoke(GeoPoint $from, GeoPoint $to) : Distance
    {
        $latA = deg2rad($from->latitude());
        $lngA = deg2rad($from->longitude());
        $latB = deg2rad($to->latitude());
        $lngB = deg2rad($to->longitude());
        $dLat = $latB - $latA;
        $dLon = $lngB - $lngA;
        $a    = sin($dLat / 2) * sin($dLat / 2) + cos($latA) * cos($latB) * sin($dLon / 2) * sin($dLon / 2);
        $c    = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return Distance::fromMeters($from->ellipsoid()->a() * $c);
    }
}
