<?php

declare(strict_types=1);

namespace Taavit\Trackee\Algo\Simplification;

use function abs;
use function array_merge;
use function array_slice;
use function count;
use function pow;
use function sqrt;

/**
 * @link https://en.wikipedia.org/wiki/Ramer%E2%80%93Douglas%E2%80%93Peucker_algorithm
 */
class RamerDouglasPeucker implements Simplification
{
    /** @var float */
    private $epsilon;

    public function __construct(float $epsilon)
    {
        $this->epsilon = $epsilon;
    }

    /**
     * {@inheritDoc}
     */
    public function simplify(array $data) : array
    {
        // Find the point with the maximum distance
        /** @var float $dmax */
        $dmax = 0;

        /** @var int $index */
        $index = 0;

        /** @var int $length */
        $length = count($data);

        if ($length < 3) {
            return $data;
        }

        for ($i = 1; $i < $length - 2; $i++) {
            $d = $this->perpendicularDistance(
                $data[0][0],
                $data[0][1],
                $data[$length - 1][0],
                $data[$length - 1][1],
                $data[$i][0],
                $data[$i][1]
            );
            if ($d <= $dmax) {
                continue;
            }

            $index = $i;
            $dmax  = $d;
        }

        // If max distance is greater than epsilon, recursively simplify
        if ($dmax > $this->epsilon) {
            // Recursive call
            $results1 = $this->simplify(array_slice($data, 0, $index + 1));
            $results2 = $this->simplify(array_slice($data, $index));

            // Build the result list
            $results = array_merge(array_slice($results1, 0, -1), $results2);
        } else {
            $results = [$data[0], $data[$length - 1]];
        }
        // Return the result

        return $results;
    }

    private function perpendicularDistance(
        float $x1,
        float $y1,
        float $x2,
        float $y2,
        float $x0,
        float $y0
    ) : float {
        return abs(($y2 - $y1) * $x0 - ($x2 - $x1) * $y0 + $x2 * $y1 - $y2 * $x1)/sqrt(pow($y2 - $y1, 2) + pow($x2 - $x1, 2));
    }
}
