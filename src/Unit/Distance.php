<?php

declare(strict_types=1);

namespace Taavit\Trackee\Unit;

class Distance
{
    /** @var float */
    private $meters;

    private function __construct(float $meters)
    {
        $this->meters = $meters;
    }

    public static function fromMeters(float $meters) : Distance
    {
        return new self($meters);
    }

    public function meters() : float
    {
        return $this->meters;
    }

    public static function add(Distance $a, Distance $b) : Distance
    {
        return new Distance($a->meters + $b->meters);
    }
}
