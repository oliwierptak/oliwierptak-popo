<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property\Requester;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\GeneratorPluginInterface;
use Popo\Schema\Reader\SchemaInterface;
use Popo\Schema\Reader\PropertyInterface;

class RequireMethodReturnTypeGeneratorPlugin extends AbstractGeneratorPlugin implements GeneratorPluginInterface
{
    const PATTERN = '<<REQUIRE_METHOD_RETURN_TYPE>>';

    public function generate(SchemaInterface $schema, PropertyInterface $property): string
    {
        $returnType = \sprintf(
            ': %s',
            $property->getType()
        );

        if ($this->propertyExplorer->isMixed($property->getType())) {
            $returnType = '';
        }

        return $returnType;
    }
}
