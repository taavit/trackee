<?php

declare(strict_types=1);

namespace Taavit\Trackee\Model;

use Taavit\Trackee\Reader\Reader;

use const DIRECTORY_SEPARATOR;

use function array_map;
use function realpath;
use function rtrim;
use function scandir;

class FilesystemRepository
{
    /** @var string */
    private $path;

    /** @var Reader[] */
    private $readers;

    public function __construct(string $path)
    {
        $this->path    = rtrim($path, DIRECTORY_SEPARATOR);
        $this->readers = [];
    }

    public function registerReader(Reader $reader) : void
    {
        $this->readers[] = $reader;
    }

    /**
     * @return Activity[]
     */
    public function getAll() : array
    {
        $activities = [];

        $files = array_map(
            [$this, 'normalizePath'],
            scandir($this->path)
        );

        foreach ($files as $file) {
            foreach ($this->readers as $reader) {
                if ($reader->supports($file)) {
                    $activities[] = $reader->read($file);
                    break;
                }
            }
        }
        return $activities;
    }

    public function getById(string $id) : Activity
    {
        foreach ($this->getAll() as $activity) {
            if ($activity->id() === $id) {
                return $activity;
            }
        }
        throw new \RuntimeException();
    }

    // phpcs:disable SlevomatCodingStandard.Classes.UnusedPrivateElements
    private function normalizePath(string $filename) : string
    {
        return realpath($this->path . DIRECTORY_SEPARATOR . $filename);
    }
    // phpcs:enable
}
