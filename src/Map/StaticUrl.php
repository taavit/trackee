<?php

declare(strict_types=1);

namespace Taavit\Trackee\Map;

use GuzzleHttp\Client;
use function sprintf;
use function str_replace;

class StaticUrl
{
    /** @var string */
    private $accessToken;

    public function __construct(string $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    public function readImage(string $path, int $width, int $height) : string
    {
        $client = new Client();

        // ? should be encoded to not end url to
        $path = str_replace('?', '%3f', $path);

        $response = $client->request(
            'GET',
            sprintf(
                'https://api.mapbox.com/styles/v1/mapbox/streets-v10/static/path-3+f44-0.7(%s)/auto/%dx%d',
                $path,
                $width,
                $height
            ),
            [
                'query' => [
                    'access_token' => $this->accessToken,
                ],
            ]
        );

        return $response->getBody()->__toString();
    }
}
