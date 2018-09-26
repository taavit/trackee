<?php

declare(strict_types=1);

namespace Taavit\Trackee\Algo\Simplification;

interface Simplification
{
    /**
     * @param array<int, array<int, float>> $data
     *
     * @return array<int, array<int, float>>
     **/
    public function simplify(array $data) : array;
}
