<?php

declare(strict_types=1);

namespace Taavit\Trackee\Unit;

use function abs;

class Duration
{
    /** @var int */
    private $duration;

    private function __construct(int $duration)
    {
        $this->duration = $duration;
    }

    public static function fromSeconds(int $seconds) : Duration
    {
        return new Duration($seconds);
    }

    public function toSeconds() : int
    {
        return $this->duration;
    }

    public static function between(\DateTimeInterface $dateA, \DateTimeInterface $dateB) : Duration
    {
        return new self(abs((int) $dateA->format('U') - (int) $dateB->format('U')));
    }
}
