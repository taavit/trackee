<?php

declare(strict_types=1);

namespace Taavit\Trackee\Model;

use League\Flysystem\FilesystemInterface;

use Taavit\Trackee\Reader\Reader;

class FilesystemRepository
{
    /** @var FilesystemInterface */
    private $filesystem;

    /** @var Reader[] */
    private $readers;

    public function __construct(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
        $this->readers    = [];
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
        /** @var Activity[] $activities */
        $activities = [];

        // phpcs:disable SlevomatCodingStandard.Commenting.InlineDocCommentDeclaration.InvalidFormat
        /** @var array<string, array{path: string}> $files */
        $files = $this->filesystem->listContents();
        // phpcs:enable SlevomatCodingStandard.Commenting.InlineDocCommentDeclaration.InvalidFormat

        foreach ($files as $file) {
            foreach ($this->readers as $reader) {
                $path = $file['path'];
                if ($reader->supports($path)) {
                    $content = $this->filesystem->read($path);
                    if ($content === false) {
                        throw new \RuntimeException('Cannot read file');
                    }
                    $activities[] = $reader->parse($content);
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
}
