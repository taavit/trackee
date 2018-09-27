<?php

declare(strict_types=1);

namespace Taavit\Trackee\Algo\Simplification;

use function count;

class Nth implements Simplification
{
    /** @var int */
    private $step;

    public function __construct(int $step)
    {
        $this->step = $step;
    }

    /**
     * @param Point[] $data
     *
     * @return Point[]
     **/
    public function simplify(array $data) : array
    {
        if (count($data) < 3) {
            return $data;
        }
        /** @var Point[] $results */
        $results = [$data[0]];

        for ($i = 1, $l = count($data) - 1; $i < $l; $i++) {
            if ($i % $this->step !== 0) {
                continue;
            }

            $results[] = $data[$i];
        }

        $results[] = $data[count($data) - 1];

        return $results;
    }
}
