<?php declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Schema\Dto;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\SchemaGeneratorPluginInterface;
use Popo\Schema\Reader\Schema;
use function sprintf;

class ReturnTypeGeneratorPlugin extends AbstractGeneratorPlugin implements SchemaGeneratorPluginInterface
{
    const PATTERN = '<<RETURN_TYPE>>';

    public function generate(Schema $schema): string
    {
        $extends = trim((string) $schema->getExtends());
        if ($extends !== '') {
            return $extends;
        }

        $returnValue = sprintf(
            '\%sInterface',
            $schema->getClassName()
        );

        if ($schema->isAbstract()) {
            $returnValue = sprintf(
                '\%s',
                $schema->getFullClassName()
            );
        }

        if (trim($schema->getReturnType()) !== '') {
            $returnValue = trim($schema->getReturnType());
        }

        return $returnValue;
    }
}
