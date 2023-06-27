<?php

namespace Freezemage\Cli\Argument;

use Freezemage\Cli\ArgumentType;

final class Flag implements Argument, Describable
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

    public function describe(DescriptionService $descriptionService): string
    {
        return $descriptionService->describeFlag($this);
    }

    public function shortName(): ?string
    {
        return $this->shortName;
    }
}