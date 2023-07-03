<?php

namespace Freezemage\Cli\Argument;

use Freezemage\Cli\ArgumentType;
use Freezemage\Cli\Parameter;
use OutOfRangeException;

final class Choice implements Argument, Describable, Interactable
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
        if (isset($this->defaultItem) && !$this->isSuitableAnswer($this->defaultItem)) {
            throw new OutOfRangeException("Default choice item MUST be in range [1, {$length}]");
        }
    }

    public function isSuitableAnswer(int $answer): bool
    {
        return isset($this->items[$answer - 1]);
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

    public function describe(DescriptionService $descriptionService): string
    {
        return $descriptionService->describeChoice($this);
    }

    public function shortName(): ?string
    {
        return $this->shortName;
    }

    public function defaultValue(): ?int
    {
        return $this->defaultItem ?? null;
    }

    public function interact(InteractionService $interactionService): Parameter
    {
        return $interactionService->interactChoice($this);
    }
}
