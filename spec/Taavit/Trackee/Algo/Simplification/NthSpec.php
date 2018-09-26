<?php

namespace spec\Taavit\Trackee\Algo\Simplification;

use Taavit\Trackee\Algo\Simplification\Nth;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NthSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith(2);
        $this->shouldHaveType(Nth::class);
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
        $this->beConstructedWith(2);

        $this->simplify([
            [0, 0],
            [1, 1],
            [2, 2],
        ])->shouldBe([
            [0, 0],
            [2, 2],
        ]);

        $this->simplify([
            [0, 0],
            [1, 1],
            [2, 2],
            [3, 3],
        ])->shouldBe([
            [0, 0],
            [2, 2],
            [3, 3],
        ]);
    }
}
