<?php

declare(strict_types=1);

namespace Taavit\Trackee\Algo\Simplification;

interface Point
{
    public function x() : float;
    public function y() : float;
    public function id() : string;
}
