<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property\Getter;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\GeneratorPluginInterface;
use Popo\Schema\Reader\SchemaInterface;
use Popo\Schema\Reader\PropertyInterface;

class GetMethodReturnTypeGeneratorPlugin extends AbstractGeneratorPlugin implements GeneratorPluginInterface
{
    const PATTERN = '<<GET_METHOD_RETURN_TYPE>>';

    public function generate(SchemaInterface $schema, PropertyInterface $property): string
    {
        if ($this->propertyExplorer->isMixed($property->getType())) {
            return '';
        }

        $returnType = \sprintf(
            ': ?%s',
            $property->getType()
        );

        return $returnType;
    }
}
