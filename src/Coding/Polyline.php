<?php

declare(strict_types=1);

namespace Taavit\Trackee\Coding;

use Taavit\Trackee\Geo\Unit\GeoPoint;

use function chr;
use function count;
use function round;

class Polyline
{
    /**
     * @param GeoPoint[] $geoPoints
     */
    public function encode(array $geoPoints) : string
    {
        if (count($geoPoints) === 0) {
            return '';
        }

        // Takes first element
        $latitude  = $geoPoints[0]->latitude();
        $longitude = $geoPoints[0]->longitude();
        $encoded   = $this->encodeFloat($latitude) . $this->encodeFloat($longitude);

        // And calculate next differences
        for ($i = 1, $l = count($geoPoints); $i < $l; $i++) {
            $currentLatitude  = $geoPoints[$i]->latitude();
            $currentLongitude = $geoPoints[$i]->longitude();

            $encoded .= $this->encodeFloat($currentLatitude - $latitude);
            $encoded .= $this->encodeFloat($currentLongitude - $longitude);

            $latitude  = $currentLatitude;
            $longitude = $currentLongitude;
        }

        return $encoded;
    }

    /**
     * @link https://developers.google.com/maps/documentation/utilities/polylinealgorithm
     */
    private function encodeFloat(float $value) : string
    {
        /** @var int $e5 */
        $e5 = (int) round($value * 100000); // Multiply by 1e5
        /** @var int $shifted */
        $shifted = $e5 < 0 ? ~($e5 << 1) : ($e5 << 1);
        /** @var string $result */
        $result = '';
        while ($shifted >= 0x20) { // Takes more than 5 bits
            $result   .= chr((0x20 | ($shifted & 0x1f)) + 63);
            $shifted >>= 5;
        }
        $result .= chr($shifted + 63);
        return $result;
    }
}
