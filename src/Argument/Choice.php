<?php

namespace Freezemage\Cli\Argument;

use Freezemage\Cli\ArgumentType;
use OutOfRangeException;

final class Choice implements Argument
{
    public function __construct(
        public string $name,
        public string $question,
        public array $items,
        public ?int $defaultItem = null,
        public ?string $shortName = null
    ) {
        $this->items = array_values($this->items);

        $length = count($this->items);
        if (isset($this->defaultItem) && $this->defaultItem < 1 || $this->defaultItem > $length) {
            throw new OutOfRangeException("Default choice item MUST be in range [1, {$length}]");
        }
    }

    public function type(): ArgumentType
    {
        return ArgumentType::CHOICE;
    }

    public function isRequired(): bool
    {
        return !isset($this->defaultItem);
    }

    public function name(): string
    {
        return $this->name;
    }
}