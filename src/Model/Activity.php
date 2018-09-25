<?php

declare(strict_types=1);

namespace Taavit\Trackee\Model;

use Taavit\Trackee\Geo\Calculator\Calculator;
use Taavit\Trackee\Geo\Unit\GeoPoint;
use Taavit\Trackee\Unit\Distance;
use Taavit\Trackee\Unit\Duration;
use function array_map;
use function count;

class Activity
{
    /** @var string */
    private $id;

    /** @var TrackPoint[] */
    private $trackPoints;

    /** @var Distance */
    private $distance;

    /** @var bool */
    private $calculated;

    public function __construct(string $id)
    {
        $this->id          = $id;
        $this->distance    = Distance::fromMeters(0);
        $this->trackPoints = [];
        $this->calculated  = true;
    }

    public function id() : string
    {
        return $this->id;
    }

    /**
     * @return TrackPoint[]
     */
    public function trackPoints() : array
    {
        return $this->trackPoints;
    }

    public function addTrackPoint(TrackPoint $trackPoint) : void
    {
        $this->trackPoints[] = $trackPoint;
        $this->calculated    = false;
    }

    public function duration() : Duration
    {
        return Duration::between(
            $this->trackPoints[0]->timestamp(),
            $this->trackPoints[count($this->trackPoints) - 1]->timestamp()
        );
    }

    public function calculateDistance(Calculator $calculator) : void
    {
        $this->distance = Distance::fromMeters(0);
        if (count($this->trackPoints) <= 1) {
            $this->calculated = true;
            return;
        }

        for ($i = 1, $l = count($this->trackPoints); $i < $l; $i++) {
            $this->distance = Distance::add(
                $this->distance,
                $calculator(
                    $this->trackPoints[$i - 1]->geoPoint(),
                    $this->trackPoints[$i]->geoPoint()
                )
            );
        }
        $this->calculated = true;
    }

    public function distance() : Distance
    {
        if (! $this->calculated) {
            throw new DistanceNotCalculated();
        }
        return $this->distance;
    }

    /**
     * @return GeoPoint[]
     */
    public function geoPoints() : array
    {
        /** @var GeoPoint[] $geoPoints */
        $geoPoints = array_map('self::extractGeoPoint', $this->trackPoints);
        return $geoPoints;
    }

    // phpcs:disable SlevomatCodingStandard.Classes.UnusedPrivateElements.UnusedMethod
    private static function extractGeoPoint(TrackPoint $point) : GeoPoint
    {
        return $point->geoPoint();
    }
    // phpcs:enable
}
