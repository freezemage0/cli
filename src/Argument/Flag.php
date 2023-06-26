<?php

namespace Freezemage\Cli\Argument;

use Freezemage\Cli\ArgumentType;

final class Flag implements Argument
{
    public function __construct(
        public string $name,
        public string $question,
        public ?bool $defaultValue = null,
        public ?string $shortName = null,
    ) {
    }

    public function type(): ArgumentType
    {
        return ArgumentType::FLAG;
    }

    public function isRequired(): bool
    {
        return !isset($this->defaultValue);
    }

    public function name(): string
    {
        return $this->name;
    }
}