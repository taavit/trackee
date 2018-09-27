<?php

declare(strict_types=1);

namespace Taavit\Trackee\Processor;

use Taavit\Trackee\Algo\Simplification\Point;
use Taavit\Trackee\Model\TrackPoint;

class PointWrapper implements Point
{
    /** @var TrackPoint */
    private $point;

    public function __construct(TrackPoint $point)
    {
        $this->point = $point;
    }

    public function x() : float
    {
        return $this->point->geoPoint()->latitude();
    }

    public function y() : float
    {
        return $this->point->geoPoint()->latitude();
    }

    public function id() : string
    {
        return $this->point->timestamp()->format(\DateTime::ATOM);
    }

    public function trackPoint() : TrackPoint
    {
        return $this->point;
    }
}
