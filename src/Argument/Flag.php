<?php

namespace Freezemage\Cli\Argument;

use Freezemage\Cli\ArgumentType;
use Freezemage\Cli\Parameter;


final class Flag implements Argument, Describable, Interactable
{
    public function __construct(
        public string $name,
        public string $question,
        public ?bool $defaultValue = false,
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

    public function defaultValue(): ?bool
    {
        return $this->defaultValue;
    }

    public function interact(InteractionService $interactionService): Parameter
    {
        return $interactionService->interactFlag($this);
    }
}
