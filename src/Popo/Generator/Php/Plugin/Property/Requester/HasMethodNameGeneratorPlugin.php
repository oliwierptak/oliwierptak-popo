<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property\Requester;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\GeneratorPluginInterface;
use Popo\Schema\Reader\SchemaInterface;
use Popo\Schema\Reader\PropertyInterface;

class HasMethodNameGeneratorPlugin extends AbstractGeneratorPlugin implements GeneratorPluginInterface
{
    const PATTERN = '<<HAS_METHOD_NAME>>';

    public function generate(SchemaInterface $schema, PropertyInterface $property): string
    {
        $name = $property->getName();

        return 'has' . \ucfirst($name);
    }
}
