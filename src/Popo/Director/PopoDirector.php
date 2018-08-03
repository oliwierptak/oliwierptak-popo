<?php

declare(strict_types = 1);

namespace Popo\Director;

use Popo\Builder\BuilderConfigurator;
use Popo\Generator\Php\Plugin\Property\Setter\Dto\SetMethodReturnTypeGeneratorPlugin as DtoSetMethodReturnTypeGeneratorPlugin;
use Popo\Generator\Php\Plugin\Property\Setter\Popo\SetMethodReturnTypeGeneratorPlugin as PopoSetMethodReturnTypeGeneratorPlugin;
use Popo\Generator\Php\Plugin\Schema\Dto\ImplementsInterfaceGeneratorPlugin as DtoImplementsInterfaceGeneratorPlugin;
use Popo\Generator\Php\Plugin\Schema\Dto\ReturnTypeGeneratorPlugin as DtoReturnTypeGeneratorPlugin;
use Popo\Generator\Php\Plugin\Schema\Popo\ImplementsInterfaceGeneratorPlugin as PopoImplementsInterfaceGeneratorPlugin;
use Popo\Generator\Php\Plugin\Schema\Popo\ReturnTypeGeneratorPlugin as PopoReturnTypeGeneratorPlugin;

class PopoDirector extends AbstractPopoDirector implements PopoDirectorInterface
{
    public function generateDto(BuilderConfigurator $configurator): void
    {
        $configurator = $this->configureDtoPlugins($configurator);
        $this->generate($configurator);
        $this->generateDtoInterfaces($configurator);
    }

    protected function generateDtoInterfaces(BuilderConfigurator $configurator): void
    {
        $configurator
            ->setExtension('Interface' . $configurator->getExtension())
            ->getSchemaConfigurator()
                ->setSchemaTemplateFilename('interface/php.interface.schema.tpl')
                ->setPropertyTemplateFilename('interface/php.interface.property.tpl')
                ->setCollectionTemplateFilename('interface/php.interface.collection.tpl');

        $this->generate($configurator);
    }

    public function generatePopo(BuilderConfigurator $configurator): void
    {
        $configurator = $this->configurePopoPlugins($configurator);
        $this->generate($configurator);
    }

    protected function configureDtoPlugins(BuilderConfigurator $configurator): BuilderConfigurator
    {
        $configurator
            ->setSchemaPluginClasses([
                DtoImplementsInterfaceGeneratorPlugin::PATTERN => DtoImplementsInterfaceGeneratorPlugin::class,
                DtoReturnTypeGeneratorPlugin::PATTERN => DtoReturnTypeGeneratorPlugin::class,
            ])
            ->setPropertyPluginClasses([
                DtoSetMethodReturnTypeGeneratorPlugin::PATTERN => DtoSetMethodReturnTypeGeneratorPlugin::class,
            ])
            ->setCollectionPluginClasses([
                DtoSetMethodReturnTypeGeneratorPlugin::PATTERN => DtoSetMethodReturnTypeGeneratorPlugin::class,
            ]);

        return $configurator;
    }

    protected function configurePopoPlugins(BuilderConfigurator $configurator): BuilderConfigurator
    {
        $configurator
            ->setSchemaPluginClasses([
                PopoImplementsInterfaceGeneratorPlugin::PATTERN => PopoImplementsInterfaceGeneratorPlugin::class,
                PopoReturnTypeGeneratorPlugin::PATTERN => PopoReturnTypeGeneratorPlugin::class,
            ])
            ->setPropertyPluginClasses([
                PopoSetMethodReturnTypeGeneratorPlugin::PATTERN => PopoSetMethodReturnTypeGeneratorPlugin::class,
            ])
            ->setCollectionPluginClasses([
                PopoSetMethodReturnTypeGeneratorPlugin::PATTERN => PopoSetMethodReturnTypeGeneratorPlugin::class,
            ]);

        return $configurator;
    }
}
