<?php

namespace spec\Taavit\Trackee\Algo\Simplification;

use Taavit\Trackee\Algo\Simplification\RamerDouglasPeucker;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RamerDouglasPeuckerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith(1);
        $this->shouldHaveType(RamerDouglasPeucker::class);
    }

    function it_shouldnt_simplify_short_list()
    {
        $this->beConstructedWith(2);
        $this->simplify([])->shouldBe([]);
        $this->simplify([[0, 0]])->shouldBe([[0, 0]]);
        $this->simplify([[0, 0], [1, 1]])->shouldBe([[0, 0], [1, 1]]);
    }

    function it_should_simplify_longer_lists()
    {
        $this->beConstructedWith(1);

        $this->simplify([
            [0, 0.0],
            [1, 0.25],
            [2, -0.25],
            [3, -0.7],
            [4, 0.75],
            [5, 0.99],
            [6, -0.99],
            [7, 0.0],
        ])->shouldBe([
            [0, 0.0],
            [7, 0.0],
        ]);

        $this->simplify([
            [0, 0.0],
            [1, 1.0],
            [1.25, 1.25],
            [1.50, 1.50],
            [2, 2.0],
            [3, 1.0],
            [4, 0.0],
        ])->shouldBe([
            [0, 0.0],
            [2, 2.0],
            [4, 0.0],
        ]);

        $this->simplify([
            [0, 0.0],
            [1, 1.0],
            [1.25, 1.35],
            [1.50, 1.53],
            [2, 2.0],
            [3, 1.0],
            [4, 0.0],
        ])->shouldBe([
            [0, 0.0],
            [2, 2.0],
            [4, 0.0],
        ]);

    }
}
