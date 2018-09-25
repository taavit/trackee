<?php
    require_once __DIR__.'/../vendor/autoload.php';

    use Taavit\Trackee\Reader\TcxReader;
    use Taavit\Trackee\Model\FilesystemRepository;
    use Taavit\Trackee\Geo\Calculator\Flat as FlatCalculator;
    use League\Flysystem\Filesystem;
    use League\Flysystem\Adapter\Local;

    $adapter = new Local(__DIR__.'/../var/data');
    $activityFilesystem = new Filesystem($adapter);

    $repository = new FilesystemRepository($activityFilesystem);

    $repository->registerReader(new TcxReader());
    $calculator = new FlatCalculator();

    $activity = $repository->getById($_GET['id']);

    $activity->calculateDistance($calculator);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Trackee</title>
</head>
<body>
    <h1>Trackee</h1>
    <h2>Aktywność: <?php echo $activity->id() ?></h2>
    <img src="image.php?id=<?php echo $activity->id() ?>" />
</body>
</html>
