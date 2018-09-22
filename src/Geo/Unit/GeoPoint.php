<?php

declare(strict_types=1);

namespace Taavit\Trackee\Geo\Unit;

class GeoPoint
{
    /** @var float */
    private $latitude;

    /** @var float */
    private $longitude;

    /** @var Ellipsoid */
    private $ellipsoid;

    public function __construct(
        float $latitude,
        float $longitude,
        Ellipsoid $ellipsoid
    ) {
        if ($latitude < -180.0 || $latitude > 180) {
            throw new \InvalidArgumentException('Latitude out of range');
        }

        if ($longitude < -180.0 || $longitude > 180) {
            throw new \InvalidArgumentException('Longitude out of range');
        }

        $this->latitude  = $latitude;
        $this->longitude = $longitude;
        $this->ellipsoid = $ellipsoid;
    }

    public function latitude() : float
    {
        return $this->latitude;
    }

    public function longitude() : float
    {
        return $this->longitude;
    }

    public function ellipsoid() : Ellipsoid
    {
        return $this->ellipsoid;
    }

    public static function areComparable(GeoPoint $point1, GeoPoint $point2) : bool
    {
        return $point1->ellipsoid === $point2->ellipsoid;
    }
}
