<?php

declare(strict_types=1);

namespace Taavit\Trackee\Model;

use Taavit\Trackee\Geo\Unit\GeoPoint;

class TrackPoint
{
    /** @var GeoPoint */
    private $geoPoint;

    /** @var \DateTimeImmutable */
    private $timestamp;

    public function __construct(GeoPoint $geoPoint, \DateTimeImmutable $timestamp)
    {
        $this->geoPoint  = $geoPoint;
        $this->timestamp = $timestamp;
    }

    public function geoPoint() : GeoPoint
    {
        return $this->geoPoint;
    }

    public function timestamp() : \DateTimeImmutable
    {
        return $this->timestamp;
    }
}
