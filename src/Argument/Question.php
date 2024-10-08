<?php

declare(strict_types=1);

namespace Freezemage\Cli\Argument;

use Freezemage\Cli\ArgumentType;
use Freezemage\Cli\Parameter;

final class Question implements Argument, Describable, Interactable
{
    public function __construct(
        public string $name,
        public string $question,
        public ?string $defaultAnswer = null,
        public ?string $shortName = null
    ) {
    }

    public function type(): ArgumentType
    {
        return ArgumentType::Question;
    }

    public function isRequired(): bool
    {
        return !isset($this->defaultAnswer);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function shortName(): ?string
    {
        return $this->shortName;
    }

    public function describe(DescriptionService $descriptionService): string
    {
        return $descriptionService->describeQuestion($this);
    }

    public function defaultValue(): ?string
    {
        return $this->defaultAnswer ?? null;
    }

    public function interact(InteractionService $interactionService): Parameter
    {
        return $interactionService->interactQuestion($this);
    }
}
