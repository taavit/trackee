<?php

declare(strict_types=1);

namespace Taavit\Trackee\Reader;

use SimpleXMLElement;
use Taavit\Trackee\Geo\Unit\Ellipsoid;
use Taavit\Trackee\Geo\Unit\GeoPoint;
use Taavit\Trackee\Model\Activity;
use Taavit\Trackee\Model\TrackPoint;
use const PATHINFO_EXTENSION;
use function file_get_contents;
use function in_array;
use function pathinfo;

class TcxReader implements Reader
{
    public function read(string $filename) : Activity
    {
        return $this->parse(file_get_contents($filename));
    }

    public function supports(string $filename) : bool
    {
        return in_array(
            pathinfo($filename, PATHINFO_EXTENSION),
            ['tcx']
        );
    }

    public function parse(string $tcxStream) : Activity
    {
        $data        = new SimpleXMLElement($tcxStream);
        $ellipsoid   = new Ellipsoid('WGS 84', 6378137, 298.257223563);
        $trackPoints = [];
        $activity    = new Activity((string) $data->Activities->Activity->Id);
        foreach ($data->Activities->Activity->Lap->Track->children() as $trackPoint) {
            $activity->addTrackPoint(
                new TrackPoint(
                    new GeoPoint(
                        (float) $trackPoint->Position->LatitudeDegrees,
                        (float) $trackPoint->Position->LongitudeDegrees,
                        $ellipsoid
                    ),
                    new \DateTimeImmutable((string) $trackPoint->Time)
                )
            );
        }
        return $activity;
    }
}
