<?php

namespace Freezemage\Cli\Argument;

use Freezemage\Cli\ArgumentType;

final class Question implements Argument, Describable
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
        return ArgumentType::QUESTION;
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
}
