<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property\Requester;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\PropertyGeneratorPluginInterface;
use Popo\Schema\Reader\PropertyInterface;

class RequireMethodReturnDockblockGeneratorPlugin extends AbstractGeneratorPlugin implements PropertyGeneratorPluginInterface
{
    const PATTERN = '<<REQUIRE_METHOD_RETURN_DOCKBLOCK>>';

    public function generate(PropertyInterface $property): string
    {
        $docblock = \trim($property->getDocblock());
        if ($docblock !== '') {
            $docblock = ' ' . $docblock;
        }

        $string = \sprintf(
            '<<DOCBLOCK_TYPE>>%s',
            $docblock
        );

        return $string;
    }
}
