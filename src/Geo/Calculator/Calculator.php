<?php

declare(strict_types=1);

namespace Taavit\Trackee\Geo\Calculator;

use Taavit\Trackee\Geo\Unit\GeoPoint;
use Taavit\Trackee\Unit\Distance;

interface Calculator
{
    public function __invoke(GeoPoint $from, GeoPoint $to) : Distance;
}
