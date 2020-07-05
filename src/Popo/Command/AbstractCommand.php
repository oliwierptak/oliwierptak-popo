<?php

declare(strict_types = 1);

namespace Popo\Command;

use Popo\Builder\BuilderConfigurator;
use Popo\PopoFacade;
use Popo\PopoFacadeInterfaces;
use Popo\Schema\SchemaConfigurator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use function array_merge;
use function getcwd;
use function is_file;
use function parse_ini_file;
use function rtrim;
use function sprintf;
use const DIRECTORY_SEPARATOR;

abstract class AbstractCommand extends Command
{
    const COMMAND_NAME = 'unknown';
    const COMMAND_DESCRIPTION = 'unknown';

    const OPTION_SCHEMA = 'schema';
    const OPTION_TEMPLATE = 'template';
    const OPTION_OUTPUT = 'output';
    const OPTION_NAMESPACE = 'namespace';
    const OPTION_EXTENSION = 'extension';
    const OPTION_IS_ABSTRACT = 'abstract';

    /**
     * @var \Popo\PopoFacadeInterfaces
     */
    protected $facade;

    abstract protected function executeCommand(InputInterface $input, OutputInterface $output): ?int;

    public function setFacade(PopoFacadeInterfaces $facade): void
    {
        $this->facade = $facade;
    }

    protected function getFacade(): PopoFacadeInterfaces
    {
        if ($this->facade === null) {
            $this->facade = new PopoFacade();
        }

        return $this->facade;
    }

    protected function configure(): void
    {
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->setDefinition([
                new InputOption(static::OPTION_SCHEMA, 's', InputOption::VALUE_OPTIONAL, 'Schema directory', 'popo/'),
                new InputOption(static::OPTION_TEMPLATE, 't', InputOption::VALUE_OPTIONAL, 'Template directory', 'vendor/popo/generator/templates/'),
                new InputOption(static::OPTION_OUTPUT, 'o', InputOption::VALUE_OPTIONAL, 'Directory for generated files', 'src/Popo/'),
                new InputOption(static::OPTION_NAMESPACE, 'm', InputOption::VALUE_OPTIONAL, 'Namespace for generated files', 'Popo'),
                new InputOption(static::OPTION_EXTENSION, 'x', InputOption::VALUE_OPTIONAL, 'Extension of generated files', '.php'),
                new InputOption(static::OPTION_IS_ABSTRACT, 'a', InputOption::VALUE_OPTIONAL, 'Setting it to true will generate abstract classes', null),
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $configurator = $this->buildConfigurator($input);

        $info = sprintf(
            "Generating POPO files...\n  schema:\t%s\n  template:\t%s\n  output:\t%s\n  namespace:\t%s\n  extension:\t%s\n  abstract:\t%d\n",
            $configurator->getSchemaDirectory(),
            $configurator->getTemplateDirectory(),
            $configurator->getOutputDirectory(),
            $configurator->getNamespace(),
            $configurator->getExtension(),
            (int)$configurator->getIsAbstract()
        );

        $output->write($info);

        return $this->executeCommand($input, $output);
    }

    protected function buildConfigurator(InputInterface $input): BuilderConfigurator
    {
        $arguments = $this->getDotData($input);

        $configurator = (new BuilderConfigurator())
            ->setSchemaConfigurator(new SchemaConfigurator())
            ->setSchemaDirectory($arguments['schema'])
            ->setTemplateDirectory($arguments['template'])
            ->setOutputDirectory($arguments['output'])
            ->setNamespace($arguments['namespace'])
            ->setExtension($arguments['extension'])
            ->setIsAbstract(((bool)$arguments['abstract']) ?? null);

        return $configurator;
    }

    protected function getDotData(InputInterface $input): array
    {
        $config = $this->getDotConfig($input);

        $arguments = [
            static::OPTION_SCHEMA => $input->getOption(static::OPTION_SCHEMA),
            static::OPTION_TEMPLATE => $input->getOption(static::OPTION_TEMPLATE),
            static::OPTION_OUTPUT => $input->getOption(static::OPTION_OUTPUT),
            static::OPTION_NAMESPACE => $input->getOption(static::OPTION_NAMESPACE),
            static::OPTION_EXTENSION => $input->getOption(static::OPTION_EXTENSION),
            static::OPTION_IS_ABSTRACT => $input->getOption(static::OPTION_IS_ABSTRACT),
        ];

        $result = array_merge($arguments, $config);

        return $result;
    }

    protected function getDotConfig(InputInterface $input): array
    {
        $config = [];
        $default = [
            GeneratePopoCommand::COMMAND_NAME => [],
            GenerateDtoCommand::COMMAND_NAME => [],
        ];

        $dotPath = rtrim(getcwd(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $configFile = $dotPath . '.popo';
        if (is_file($configFile)) {
            $config = parse_ini_file($configFile, true) ?? $default;
        }

        return $config[static::COMMAND_NAME];
    }
}
