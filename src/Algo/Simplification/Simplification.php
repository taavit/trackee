<?php

declare(strict_types=1);

namespace Taavit\Trackee\Algo\Simplification;

interface Simplification
{
    /**
     * @param Point[] $data
     *
     * @return Point[]
     **/
    public function simplify(array $data) : array;
}
