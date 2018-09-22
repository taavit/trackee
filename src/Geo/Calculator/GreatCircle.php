<?php

declare(strict_types=1);

namespace Taavit\Trackee\Geo\Calculator;

use Taavit\Trackee\Geo\Unit\GeoPoint;
use Taavit\Trackee\Unit\Distance;

use function acos;
use function cos;
use function deg2rad;
use function sin;

class GreatCircle implements Calculator
{
    public function __invoke(GeoPoint $from, GeoPoint $to) : Distance
    {
        $latA = deg2rad($from->latitude());
        $lngA = deg2rad($from->longitude());
        $latB = deg2rad($to->latitude());
        $lngB = deg2rad($to->longitude());

        $degrees = acos(sin($latA) * sin($latB) + cos($latA) * cos($latB) * cos($lngB - $lngA));

        return Distance::fromMeters($degrees * $from->ellipsoid()->a());
    }
}
