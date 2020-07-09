<?php

declare(strict_types = 1);

namespace Popo\Builder;

use Popo\Schema\SchemaConfigurator;
use Popo\Schema\SchemaConfiguratorInterface;

class BuilderConfigurator
{
    /**
     * @var string
     */
    protected $schemaDirectory;

    /**
     * @var string
     */
    protected $templateDirectory;

    /**
     * @var string
     */
    protected $outputDirectory;

    /**
     * @var string
     */
    protected $namespace = '\\';

    /**
     * @var string
     */
    protected $extension = '.php';

    /**
     * @var bool|null if set, will overwrite the abstract value from schema file
     */
    protected $isAbstract = null;

    /**
     * @var string|null if set, the generated classes will be extended with this class
     */
    protected $extends = null;

    /**
     * @var \Popo\Schema\SchemaConfiguratorInterface
     */
    protected $schemaConfigurator;

    /**
     * @var \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[]
     */
    protected $schemaPluginClasses = [];

    /**
     * @var \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[]
     */
    protected $propertyPluginClasses = [];

    /**
     * @var \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[]
     */
    protected $collectionPluginClasses = [];

    public function __construct()
    {
        $this->schemaConfigurator = new SchemaConfigurator();
    }

    public function getSchemaDirectory(): string
    {
        return $this->schemaDirectory;
    }

    public function setSchemaDirectory(string $schemaDirectory): self
    {
        $this->schemaDirectory = $schemaDirectory;

        return $this;
    }

    public function getTemplateDirectory(): string
    {
        return $this->templateDirectory;
    }

    public function setTemplateDirectory(string $templateDirectory): self
    {
        $this->templateDirectory = $templateDirectory;

        return $this;
    }

    public function getOutputDirectory(): string
    {
        return $this->outputDirectory;
    }

    public function setOutputDirectory(string $outputDirectory): self
    {
        $this->outputDirectory = $outputDirectory;

        return $this;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function setNamespace(string $namespace): self
    {
        $this->namespace = $namespace;

        return $this;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    public function getIsAbstract(): ?bool
    {
        return $this->isAbstract;
    }

    public function setIsAbstract(?bool $isAbstract): self
    {
        $this->isAbstract = $isAbstract;

        return $this;
    }

    public function getExtends(): ?string
    {
        return $this->extends;
    }

    public function setExtends(?string $extends): self
    {
        $this->extends = $extends;

        return $this;
    }

    public function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->schemaConfigurator;
    }

    public function setSchemaConfigurator(SchemaConfiguratorInterface $schemaConfigurator): self
    {
        $this->schemaConfigurator = $schemaConfigurator;

        return $this;
    }

    public function getSchemaPluginClasses(): array
    {
        return $this->schemaPluginClasses;
    }

    /**
     * Format of $schemaPlugins:
     *
     * [
     *  SchemaGeneratorPluginInterface::PATTERN => SchemaGeneratorPluginInterface::class,
     *  ]
     *
     * @param \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[] $schemaPluginClasses
     *
     * @return \Popo\Builder\BuilderConfigurator
     */
    public function setSchemaPluginClasses(array $schemaPluginClasses): self
    {
        $this->schemaPluginClasses = $schemaPluginClasses;

        return $this;
    }

    public function getPropertyPluginClasses(): array
    {
        return $this->propertyPluginClasses;
    }

    /**
     * Format of $propertyPlugins:
     *
     * [
     *  PropertyGeneratorPluginInterface::PATTERN => PropertyGeneratorPluginInterface::class,
     *  ]
     *
     * @param \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[] $propertyPluginClasses
     *
     * @return \Popo\Builder\BuilderConfigurator
     */
    public function setPropertyPluginClasses(array $propertyPluginClasses): self
    {
        $this->propertyPluginClasses = $propertyPluginClasses;

        return $this;
    }

    public function getCollectionPluginClasses(): array
    {
        return $this->collectionPluginClasses;
    }

    /**
     * Format of $propertyPlugins:
     *
     * [
     *  PropertyGeneratorPluginInterface::PATTERN => PropertyGeneratorPluginInterface::class,
     *  ]
     *
     * @param \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[] $collectionPluginClasses
     *
     * @return \Popo\Builder\BuilderConfigurator
     */
    public function setCollectionPluginClasses(array $collectionPluginClasses): self
    {
        $this->collectionPluginClasses = $collectionPluginClasses;

        return $this;
    }
}
