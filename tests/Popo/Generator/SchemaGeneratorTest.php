<?php

declare(strict_types = 1);

namespace Tests\Popo\Generator;

use PHPUnit\Framework\TestCase;
use Popo\Builder\BuilderConfigurator;
use Popo\Builder\GeneratorBuilder;
use Popo\Builder\GeneratorBuilderInterface;
use Popo\Builder\PluginContainer;
use Popo\Builder\PluginContainerInterface;
use Popo\Generator\GeneratorInterface;
use Popo\Generator\Php\Plugin\Property\Setter\Dto\SetMethodReturnTypeGeneratorPlugin as DtoSetMethodReturnTypeGeneratorPlugin;
use Popo\Generator\Php\Plugin\Schema\Dto\ImplementsInterfaceGeneratorPlugin as DtoImplementsInterfaceGeneratorPlugin;
use Popo\Generator\Php\Plugin\Schema\Dto\ReturnTypeGeneratorPlugin as DtoReturnTypeGeneratorPlugin;
use Popo\PopoFactory;
use Popo\Schema\Reader\PropertyExplorerInterface;
use Popo\Schema\Reader\SchemaInterface;
use Popo\Schema\SchemaConfigurator;

class SchemaGeneratorTest extends TestCase
{
    /**
     * @var array
     */
    protected $schema;

    /**
     * @var string
     */
    protected $schemaDirectory;

    /**
     * @var string
     */
    protected $templateDirectory;

    /**
     * @var \Popo\PopoFactoryInterface
     */
    protected $popoFactory;

    protected function setUp(): void
    {
        $this->popoFactory = new PopoFactory();

        $this->schemaDirectory = \Popo\TESTS_DIR . 'fixtures/dto/bundles/';
        $this->templateDirectory = \Popo\APPLICATION_DIR . 'templates/';

        $this->schema = [
            'name' => 'Popo\Tests\FooStub',
            'schema' => [[
                'name' => 'id',
                'type' => 'int',
                'docblock' => 'Lorem Ipsum',
            ],[
                'name' => 'username',
                'type' => 'string',
            ],[
                'name' => 'password',
                'type' => 'string',
            ],[
                'name' => 'optionalData',
                'collectionItem' => 'string',
                'type' => 'array',
                'default' => [],
            ],[
                'name' => 'bar',
                'type' => '\\Popo\\Tests\\BarStubInterface',
            ]],
        ];
    }

    protected function buildGeneratorBuilder(): GeneratorBuilderInterface
    {
        $generatorBuilder = new GeneratorBuilder(
            $this->popoFactory->createLoaderFactory()->createContentLoader(),
            $this->popoFactory->createGeneratorFactory(),
            $this->popoFactory->createSchemaFactory()
        );

        return $generatorBuilder;
    }

    protected function buildPropertyExplorer(): PropertyExplorerInterface
    {
        $propertyExplorer = $this->popoFactory
            ->createSchemaFactory()
            ->createPropertyExplorer();

        return $propertyExplorer;
    }

    protected function buildSchema(): SchemaInterface
    {
        $schema = $this->popoFactory
            ->createReaderFactory()->createSchema($this->schema);

        return $schema;
    }

    protected function buildPluginContainer(): PluginContainerInterface
    {
        $propertyExplorer = $this->buildPropertyExplorer();

        $pluginContainer = (new PluginContainer($propertyExplorer))
            ->registerSchemaClassPlugins([
                DtoImplementsInterfaceGeneratorPlugin::PATTERN => DtoImplementsInterfaceGeneratorPlugin::class,
                DtoReturnTypeGeneratorPlugin::PATTERN => DtoReturnTypeGeneratorPlugin::class,
            ])
            ->registerPropertyClassPlugins([
                DtoSetMethodReturnTypeGeneratorPlugin::PATTERN => DtoSetMethodReturnTypeGeneratorPlugin::class,
            ])
            ->registerCollectionClassPlugins([
                DtoSetMethodReturnTypeGeneratorPlugin::PATTERN => DtoSetMethodReturnTypeGeneratorPlugin::class,
            ]);

        return $pluginContainer;
    }

    protected function buildGenerator(BuilderConfigurator $configurator): GeneratorInterface
    {
        $pluginContainer = $this->buildPluginContainer();
        $generatorBuilder = $this->buildGeneratorBuilder();

        $generator = $generatorBuilder->build($configurator, $pluginContainer);

        return $generator;
    }

    public function testGenerate(): void
    {
        $configurator = (new BuilderConfigurator())
            ->setSchemaConfigurator((new SchemaConfigurator()))
            ->setTemplateDirectory($this->templateDirectory)
            ->setSchemaDirectory($this->schemaDirectory);

        $generator = $this->buildGenerator($configurator);
        $schema = $this->buildSchema();

        $schemaString = $generator->generate($schema);

        $expectedString = '
<?php

declare(strict_types = 1);

namespace Popo\Tests;

/**
 * Code generated by POPO generator, do not edit.
 */
class FooStub implements \Popo\Tests\FooStubInterface
{
    /**
     * @var array
     */
    protected $data = array (
  \'optionalData\' => 
  array (
  ),
);

    /**
     * @var array
     */
    protected $default = array (
  \'optionalData\' => 
  array (
  ),
);

    /**
    * @var array
    */
    protected $propertyMapping = array (
  \'id\' => \'int\',
  \'username\' => \'string\',
  \'password\' => \'string\',
  \'optionalData\' => \'array\',
  \'bar\' => \'\\\\Popo\\\\Tests\\\\BarStubInterface\',
);

    /**
    * @var array
    */
    protected $collectionItems = array (
  \'id\' => \'\',
  \'username\' => \'\',
  \'password\' => \'\',
  \'optionalData\' => \'string\',
  \'bar\' => \'\',
);

    /**
     * @param string $property
     *
     * @return mixed|null
     */
    protected function popoGetValue(string $property)
    {
        if (!isset($this->data[$property])) {
            return null;
        }

        return $this->data[$property];
    }

    /**
     * @param string $property
     * @param mixed $value
     *
     * @return void
     */
    protected function popoSetValue(string $property, $value): void
    {
        $this->data[$property] = $value;
    }

    /**
     * @return array
     */
    protected function getPropertyNames(): array
    {
        return \array_keys(
            $this->propertyMapping
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $data = [];
        foreach ($this->propertyMapping as $key => $type) {
            $data[$key] = null;

            if (isset($this->data[$key])) {
                $value = $this->data[$key];

                if ($this->collectionItems[$key] !== \'\') {
                    if (\is_array($value) && \class_exists($this->collectionItems[$key])) {
                        foreach ($value as $popo) {
                            if (\method_exists($popo, \'toArray\')) {
                                $data[$key][] = $popo->toArray();
                            }
                        }
                    }
                } else {
                    $data[$key] = $value;
                }

                if (\is_object($value) && \method_exists($value, \'toArray\')) {
                    $data[$key] = $value->toArray();
                }
            }
        }

        return $data;
    }

    /**
     * @param array $data
     *
     * @return \Popo\Tests\FooStubInterface
     */
    public function fromArray(array $data): \Popo\Tests\FooStubInterface
    {
        $result = [];
        foreach ($this->propertyMapping as $key => $type) {
            $result[$key] = null;
            if (\array_key_exists($key, $this->default)) {
                $result[$key] = $this->default[$key];
            }
            if (\array_key_exists($key, $data)) {
                if ($this->collectionItems[$key] !== \'\') {
                    if (\is_array($data[$key]) && \class_exists($this->collectionItems[$key])) {
                        foreach ($data[$key] as $popoData) {
                            $popo = new $this->collectionItems[$key]();
                            if (\method_exists($popo, \'fromArray\')) {
                                $popo->fromArray($popoData);
                            }
                            $result[$key][] = $popo;
                        }
                    }
                } else {
                    $result[$key] = $data[$key];
                }
            }

            if (\is_array($result[$key]) && \class_exists($type)) {
                $popo = new $type();
                if (\method_exists($popo, \'fromArray\')) {
                    $popo->fromArray($result[$key]);
                }
                $result[$key] = $popo;
            }
        }

        $this->data = $result;

        return $this;
    }

    /**
     * @param string $property
     *
     * @throws \UnexpectedValueException
     * @return void
     */
    protected function assertPropertyValue(string $property): void
    {
        if (!isset($this->data[$property])) {
            throw new \UnexpectedValueException(\sprintf(
                \'Required value of "%s" has not been set\',
                $property
            ));
        }
    }

    /**
     * @param string $propertyName
     * @param mixed $value
     *
     * @return void
     */
    protected function addCollectionItem(string $propertyName, $value): void
    {
        $type = \trim(\strtolower($this->propertyMapping[$propertyName]));
        $collection = $this->popoGetValue($propertyName) ?? [];

        if (!\is_array($collection) || $type !== \'array\') {
            throw new \InvalidArgumentException(\'Cannot add item to non array type: \' . $propertyName);
        }

        $collection[] = $value;

        $this->popoSetValue($propertyName, $collection);
    }

    
    /**
     * @return integer|null Lorem Ipsum
     */
    public function getId(): ?int
    {
        return $this->popoGetValue(\'id\');
    }

    /**
     * @param integer|null $id Lorem Ipsum
     *
     * @return self Lorem Ipsum
     */
    public function setId(?int $id): \Popo\Tests\FooStubInterface
    {
        $this->popoSetValue(\'id\', $id);

        return $this;
    }

    /**
     * @throws \UnexpectedValueException
     *
     * @return integer Lorem Ipsum
     */
    public function requireId(): int
    {
        $this->assertPropertyValue(\'id\');

        return (int)$this->popoGetValue(\'id\');
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->popoGetValue(\'username\');
    }

    /**
     * @param string|null $username
     *
     * @return self
     */
    public function setUsername(?string $username): \Popo\Tests\FooStubInterface
    {
        $this->popoSetValue(\'username\', $username);

        return $this;
    }

    /**
     * @throws \UnexpectedValueException
     *
     * @return string
     */
    public function requireUsername(): string
    {
        $this->assertPropertyValue(\'username\');

        return (string)$this->popoGetValue(\'username\');
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->popoGetValue(\'password\');
    }

    /**
     * @param string|null $password
     *
     * @return self
     */
    public function setPassword(?string $password): \Popo\Tests\FooStubInterface
    {
        $this->popoSetValue(\'password\', $password);

        return $this;
    }

    /**
     * @throws \UnexpectedValueException
     *
     * @return string
     */
    public function requirePassword(): string
    {
        $this->assertPropertyValue(\'password\');

        return (string)$this->popoGetValue(\'password\');
    }

    /**
     * @return array|null
     */
    public function getOptionalData(): ?array
    {
        return $this->popoGetValue(\'optionalData\');
    }

    /**
     * @param array|null $optionalData
     *
     * @return self
     */
    public function setOptionalData(?array $optionalData): \Popo\Tests\FooStubInterface
    {
        $this->popoSetValue(\'optionalData\', $optionalData);

        return $this;
    }

    /**
     * @throws \UnexpectedValueException
     *
     * @return array
     */
    public function requireOptionalData(): array
    {
        $this->assertPropertyValue(\'optionalData\');

        return (array)$this->popoGetValue(\'optionalData\');
    }

    /**
     * @return \Popo\Tests\BarStubInterface|null
     */
    public function getBar(): ?\Popo\Tests\BarStubInterface
    {
        return $this->popoGetValue(\'bar\');
    }

    /**
     * @param \Popo\Tests\BarStubInterface|null $bar
     *
     * @return self
     */
    public function setBar(?\Popo\Tests\BarStubInterface $bar): \Popo\Tests\FooStubInterface
    {
        $this->popoSetValue(\'bar\', $bar);

        return $this;
    }

    /**
     * @throws \UnexpectedValueException
     *
     * @return \Popo\Tests\BarStubInterface
     */
    public function requireBar(): \Popo\Tests\BarStubInterface
    {
        $this->assertPropertyValue(\'bar\');

        return $this->popoGetValue(\'bar\');
    }


    
    /**
     * @param string $optionalDataItem
     *
     * @return self
     */
    public function addOptionalDataItem(string $optionalDataItem): \Popo\Tests\FooStubInterface
    {
        $this->addCollectionItem(\'optionalData\', $optionalDataItem);

        return $this;
    }

}
';

        $this->assertEquals(\trim($expectedString), \trim($schemaString));
    }
}
