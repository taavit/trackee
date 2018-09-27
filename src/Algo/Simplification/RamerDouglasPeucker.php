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
     * @param Point[] $data
     *
     * @return Point[]
     **/
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
                $data[0],
                $data[$length - 1],
                $data[$i]
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
        Point $p1,
        Point $p2,
        Point $p0
    ) : float {
        return abs(
            ($p2->y() - $p1->y()) * $p0->x() - ($p2->x() - $p1->x()) * $p0->y() + $p2->x() * $p1->y() - $p2->y() * $p1->x()
        )/
            sqrt(pow($p2->y() - $p1->y(), 2) + pow($p2->x() - $p1->x(), 2));
    }
}
