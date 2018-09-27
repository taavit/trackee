<?php

declare(strict_types=1);

namespace Taavit\Trackee\Processor;

use Taavit\Trackee\Algo\Simplification\Simplification;
use Taavit\Trackee\Model\TrackPoint;
use function array_map;
use function count;

class Limiter
{
    /** @var Simplification */
    private $simplification;

    public function __construct(Simplification $simplification)
    {
        $this->simplification = $simplification;
    }

    /**
     * @param TrackPoint[] $trackPoints
     *
     * @return TrackPoint[]
     */
    public function process(array $trackPoints) : array
    {
        if (count($trackPoints) === 0) {
            return [];
        }

        $points    = array_map([$this, 'wrapTrackPoint'], $trackPoints);
        $processed = $this->simplification->simplify($points);
        /** @var TrackPoint[] $return */
        $return = [];

        // Unpacking because when object is passed to algo, original data type is lost
        // With generics it would be easy, but… yeah, generics.
        foreach ($processed as $processedPoint) {
            foreach ($trackPoints as $trackPoint) {
                if ($trackPoint->timestamp()->format(\DateTime::ATOM) === $processedPoint->id()) {
                    $return[] = $trackPoint;
                    break 1;
                }
            }
        }
        return $return;
    }

    // phpcs:disable SlevomatCodingStandard.Classes.UnusedPrivateElements.UnusedMethod
    private function wrapTrackPoint(TrackPoint $point) : PointWrapper
    {
        return new PointWrapper($point);
    }
    // phpcs:enable SlevomatCodingStandard.Classes.UnusedPrivateElements.UnusedMethod
}
