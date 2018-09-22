<?php

declare(strict_types=1);

namespace Taavit\Trackee\Reader;

use Taavit\Trackee\Model\Activity;

interface Reader
{
    public function read(string $filename) : Activity;
    public function supports(string $filename) : bool;
}
