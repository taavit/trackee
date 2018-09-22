<?php

declare(strict_types=1);

namespace Taavit\Trackee\Geo\Unit;

class Ellipsoid
{
    /** @var string */
    private $name;

    /** @var float */
    private $a;

    /** @var float */
    private $invF;

    public function __construct(string $name, float $a, float $invF)
    {
        $this->name = $name;
        $this->a    = $a;
        $this->invF = $invF;
    }
    public function name() : string
    {
        return $this->name;
    }

    public function a() : float
    {
        return $this->a;
    }

    public function invF() : float
    {
        return $this->invF;
    }

    public function b() : float
    {
        return $this->a() * (1 - 1 / $this->invF());
    }

    public function f() : float
    {
        return 1 / $this->invF();
    }
}
