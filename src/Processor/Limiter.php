<?php

declare(strict_types=1);

namespace Taavit\Trackee\Processor;

use Taavit\Trackee\Algo\Simplification\Simplification;
use Taavit\Trackee\Geo\Unit\Ellipsoid;
use Taavit\Trackee\Geo\Unit\GeoPoint;
use function array_map;
use function count;

class Limiter
{
    /** @var Ellipsoid */
    private $ellipsoid;

    /** @var Simplification */
    private $simplification;

    public function __construct(Simplification $simplification)
    {
        $this->simplification = $simplification;
        $this->ellipsoid      = new Ellipsoid('EMPTY', 1, 1);
    }

    /**
     * @param GeoPoint[] $geoPoints
     *
     * @return GeoPoint[]
     */
    public function process(array $geoPoints) : array
    {
        if (count($geoPoints) === 0) {
            return [];
        }
        $this->ellipsoid = $geoPoints[0]->ellipsoid();

        $points    = array_map([$this, 'pointToArray'], $geoPoints);
        $processed = $this->simplification->simplify($points);
        return array_map([$this, 'arrayToPoint'], $processed);
    }

    // phpcs:disable SlevomatCodingStandard.Classes.UnusedPrivateElements.UnusedMethod
    /**
     * @return array<int, float>
     */
    private function pointToArray(GeoPoint $point) : array
    {
        return [
            $point->latitude(),
            $point->longitude(),
        ];
    }

    /**
     * @param array<int, float> $point
     */
    private function arrayToPoint(array $point) : GeoPoint
    {
        return new GeoPoint(
            $point[0],
            $point[1],
            $this->ellipsoid
        );
    }
    // phpcs:enable SlevomatCodingStandard.Classes.UnusedPrivateElements.UnusedMethod
}
