<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Schema\Dto;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\SchemaGeneratorPluginInterface;
use Popo\Schema\Reader\SchemaInterface;
use function sprintf;

class ReturnTypeGeneratorPlugin extends AbstractGeneratorPlugin implements SchemaGeneratorPluginInterface
{
    const PATTERN = '<<RETURN_TYPE>>';

    public function generate(SchemaInterface $schema): string
    {
        return sprintf(
            '\%sInterface',
            $schema->getName()
        );
    }
}
