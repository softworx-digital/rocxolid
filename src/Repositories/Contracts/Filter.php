<?php

namespace Softworx\RocXolid\Repositories\Contracts;

// @todo - update
interface Filter
{
    const DATA_PARAM = '_filter';

    public function apply(Filterable $repository);

    public function getName(): string;

    public function getFieldName(): string;

    public function getType(): string;

    public function getValue();
}
