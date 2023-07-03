<?php

namespace Freezemage\Cli\Argument;

use Freezemage\Cli\ArgumentType;

interface Argument
{
    public function name(): string;

    public function shortName(): ?string;

    public function type(): ArgumentType;

    public function isRequired(): bool;

    public function defaultValue(): mixed;
}
