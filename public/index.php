<?php
    require_once __DIR__.'/../vendor/autoload.php';

    use Taavit\Trackee\Reader\TcxReader;
    use Taavit\Trackee\Model\FilesystemRepository;
    use Taavit\Trackee\Geo\Calculator\Flat as FlatCalculator;
    use League\Flysystem\Adapter\Local;
    use League\Flysystem\Filesystem;

    $dotenv = new \Dotenv\Dotenv(__DIR__.'/../');
    $dotenv->load();

    $adapter = new Local(__DIR__.'/../var/data');
    $activityFilesystem = new Filesystem($adapter);
    $repository = new FilesystemRepository($activityFilesystem);

    $repository->registerReader(new TcxReader());
    $calculator = new FlatCalculator();

    $activities = $repository->getAll();

    foreach ($activities as $activity) {
        $activity->calculateDistance($calculator);
    }
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
    <h2>Aktywności</h2>
    <ul>
        <?php foreach ($activities as $activity) : ?>
            <li>
                <a href="activity.php?id=<?php echo $activity->id(); ?>">
                    <?php echo $activity->id() ?>
                    (<?php echo $activity->duration()->toSeconds() ?> s.)
                    (<?php echo $activity->distance()->meters() ?> m.)
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
