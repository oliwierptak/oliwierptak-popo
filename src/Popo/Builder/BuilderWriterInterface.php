<?php

declare(strict_types = 1);

namespace Popo\Builder;

use Popo\Generator\GeneratorInterface;

interface BuilderWriterInterface
{
    /**
     * Specification:
     * - Creates collection of schema files using SchemaBuilder
     * - Merges schema files using SchemaMerger
     * - Creates BundleWriter using WriterFactory
     * - Writes merged schema files to specified output directory
     *
     * @param \Popo\Builder\BuilderConfiguratorInterface $configurator
     * @param \Popo\Generator\GeneratorInterface $generator
     *
     * @return void
     */
    public function write(BuilderConfiguratorInterface $configurator, GeneratorInterface $generator): void;
}
