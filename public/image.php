<?php
    require_once __DIR__.'/../vendor/autoload.php';

    use Taavit\Trackee\Reader\TcxReader;
    use Taavit\Trackee\Model\FilesystemRepository;
    use Taavit\Trackee\Geo\Calculator\Flat as FlatCalculator;
    use Taavit\Trackee\Coding\Polyline as PolylineCoder;
    use Taavit\Trackee\Processor\Limiter;
    use Taavit\Trackee\Algo\Simplification\RamerDouglasPeucker;
    use Taavit\Trackee\Map\StaticUrl;

    use League\Flysystem\Adapter\Local;
    use League\Flysystem\Filesystem;

    $dotenv = new \Dotenv\Dotenv(__DIR__.'/../');
    $dotenv->load();

    $adapter = new Local(__DIR__.'/../var/data');
    $activityFilesystem = new Filesystem($adapter);
    $repository = new FilesystemRepository($activityFilesystem);

    $imageAdapter = new Local(__DIR__.'/../var/cache');
    $imageFilesystem = new Filesystem($imageAdapter);

    $repository->registerReader(new TcxReader());
    $calculator = new FlatCalculator();
    const PRECISION = 0.0005;
    $limiter = new Limiter(new RamerDouglasPeucker(PRECISION));

    $activity = $repository->getById($_GET['id']);
    $staticMap = new StaticUrl(getenv('MAPBOX_API_KEY'));

    $activity->calculateDistance($calculator);
    $coder = new PolylineCoder();

    if (!$imageFilesystem->has("{$activity->id()}.png")) {
        $track = $coder->encode($limiter->process($activity->geoPoints()));
        $content = $staticMap->readImage($track, 640, 480);
        $imageFilesystem->write("{$activity->id()}.png", $content);
    }

    header('header("Content-type: image/png");');

    echo $imageFilesystem->read("{$activity->id()}.png");
