<?php

declare(strict_types=1);

namespace Taavit\Trackee\Processor;

use Taavit\Trackee\Geo\Unit\GeoPoint;
use function count;

class Limiter
{
    /**
     * @param GeoPoint[] $geoPoints
     *
     * @return GeoPoint[]
     */
    public function process(array $geoPoints, int $amount) : array
    {
        while (count($geoPoints) > $amount - 1) {
            $results = [];
            for ($i = 0, $l = count($geoPoints); $i < $l; $i++) {
                if ($i % 2 !== 0) {
                    continue;
                }

                $results[] = $geoPoints[$i];
            }
            $geoPoints = $results;
        }

        return $geoPoints;
    }
}
