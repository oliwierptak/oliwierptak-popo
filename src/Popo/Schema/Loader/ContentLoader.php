<?php declare(strict_types = 1);

namespace Popo\Schema\Loader;

use Popo\Schema\Exception\SchemaLoaderException;
use SplFileInfo;
use Throwable;
use function file_get_contents;
use function sprintf;

class ContentLoader
{
    public function load(SplFileInfo $filename): string
    {
        try {
            return file_get_contents($filename->getPathname());
        }
        catch (Throwable $e) {
            throw new SchemaLoaderException(
                sprintf(
                    'Error loading template file: "%s"',
                    $filename->getPathname()
                )
            );
        }
    }
}
