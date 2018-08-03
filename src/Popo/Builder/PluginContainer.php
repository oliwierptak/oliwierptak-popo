<?php

declare(strict_types = 1);

namespace Popo\Builder;

use Popo\Plugin\Generator\PropertyGeneratorPluginInterface;
use Popo\Plugin\Generator\SchemaGeneratorPluginInterface;
use Popo\Schema\Reader\PropertyExplorerInterface;

class PluginContainer implements PluginContainerInterface
{
    /**
     * @var \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[]
     */
    protected $schemaPlugins = [];

    /**
     * @var \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[]
     */
    protected $propertyPlugins = [];

    /**
     * @var \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[]
     */
    protected $collectionPlugins = [];

    /**
     * @var \Popo\Schema\Reader\PropertyExplorerInterface
     */
    protected $propertyExplorer;

    public function __construct(PropertyExplorerInterface $propertyExplorer)
    {
        $this->propertyExplorer = $propertyExplorer;
    }

    public function registerPropertyClassPlugins(array $pluginClassCollection): PluginContainerInterface
    {
        foreach ($pluginClassCollection as $pattern => $pluginClass) {
            $plugin = new $pluginClass(
                $this->propertyExplorer
            );

            if (!($plugin instanceof PropertyGeneratorPluginInterface)) {
                continue;
            }

            $this->propertyPlugins[$pattern] = $plugin;
        }

        return $this;
    }

    public function registerCollectionClassPlugins(array $pluginClassCollection): PluginContainerInterface
    {
        foreach ($pluginClassCollection as $pattern => $pluginClass) {
            $plugin = new $pluginClass(
                $this->propertyExplorer
            );

            if (!($plugin instanceof PropertyGeneratorPluginInterface)) {
                continue;
            }

            $this->collectionPlugins[$pattern] = $plugin;
        }

        return $this;
    }

    public function registerSchemaClassPlugins(array $pluginClassCollection): PluginContainerInterface
    {
        foreach ($pluginClassCollection as $pattern => $pluginClass) {
            $plugin = new $pluginClass(
                $this->propertyExplorer
            );

            if (!($plugin instanceof SchemaGeneratorPluginInterface)) {
                continue;
            }

            $this->schemaPlugins[$pattern] = $plugin;
        }

        return $this;
    }

    public function getPropertyPlugins(): array
    {
        return $this->propertyPlugins;
    }

    public function getSchemaPlugins(): array
    {
        return $this->schemaPlugins;
    }

    public function getCollectionPlugins(): array
    {
        return $this->collectionPlugins;
    }
}
