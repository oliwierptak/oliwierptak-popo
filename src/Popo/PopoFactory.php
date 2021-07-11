<?php

declare(strict_types = 1);

namespace Popo;

use JetBrains\PhpStorm\Pure;
use Popo\Builder\PopoBuilder;
use Popo\Builder\TestBuilder;
use Popo\Inspector\SchemaValueInspector;
use Popo\Inspector\SchemaPropertyInspector;
use Popo\Model\PopoModel;
use Popo\Builder\SchemaBuilder;
use Popo\Builder\SchemaLoader;
use Popo\Model\TestModel;

class PopoFactory
{
    #[Pure] public function createPopoModel(): PopoModel
    {
        return new PopoModel(
            $this->createSchemaBuilder(),
            $this->createPopoBuilder()
        );
    }

    #[Pure] public function createTestModel(): TestModel
    {
        return new TestModel(
            $this->createSchemaBuilder(),
            $this->createTestBuilder()
        );
    }

    #[Pure] protected function createSchemaBuilder(): SchemaBuilder
    {
        return new SchemaBuilder(
            $this->createSchemaLoader()
        );
    }

    #[Pure] protected function createSchemaLoader(): SchemaLoader
    {
        return new SchemaLoader();
    }

    #[Pure] protected function createPopoBuilder(): PopoBuilder
    {
        return new PopoBuilder(
            $this->createValueTypeReader(),
            $this->createValueTypeWriter()
        );
    }

    #[Pure] protected function createTestBuilder(): TestBuilder
    {
        return new TestBuilder(
            $this->createValueTypeReader(),
            $this->createValueTypeWriter()
        );
    }

    #[Pure] protected function createValueTypeReader(): SchemaValueInspector
    {
        return new SchemaValueInspector();
    }

    #[Pure] protected function createValueTypeWriter(): SchemaPropertyInspector
    {
        return new SchemaPropertyInspector();
    }
}
