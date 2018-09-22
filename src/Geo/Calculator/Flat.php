<?php

declare(strict_types=1);

namespace Taavit\Trackee\Geo\Calculator;

use Taavit\Trackee\Geo\Unit\GeoPoint;
use Taavit\Trackee\Unit\Distance;

use function cos;
use function deg2rad;
use function sqrt;

class Flat implements Calculator
{
    public function __invoke(GeoPoint $from, GeoPoint $to) : Distance
    {
        $latA = deg2rad($from->latitude());
        $lngA = deg2rad($from->longitude());
        $latB = deg2rad($to->latitude());
        $lngB = deg2rad($to->longitude());
        $x    = ($lngB - $lngA) * cos(($latA + $latB) / 2);
        $y    = $latB - $latA;
        $d    = sqrt(($x * $x) + ($y * $y)) * $from->ellipsoid()->a();

        return Distance::fromMeters($d);
    }
}
