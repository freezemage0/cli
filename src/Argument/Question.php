<?php

namespace Freezemage\Cli\Argument;

use Freezemage\Cli\ArgumentType;

final class Question implements Argument
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
}