<?php

namespace Freezemage\Cli;

use ArrayIterator;
use Freezemage\Cli\Argument\Argument;
use Iterator;
use IteratorAggregate;
use Traversable;

/**
 * @template-implements Iterator<string, Argument>
 */
final class ArgumentList implements IteratorAggregate
{
    private array $arguments;

    public function __construct(Argument ...$arguments)
    {
        $this->arguments = $arguments;
    }

    public function insert(Argument $argument): self
    {
        $this->arguments[$argument->name] = $argument;

        return $this;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->arguments);
    }

    public function has(string $arg): bool
    {
        foreach ($this->arguments as $argument) {
            if (in_array($arg, $this->getNames($argument), true)) {
                return true;
            }
        }

        return false;
    }

    private function getNames(Argument $argument): array
    {
        $names = ["--{$argument->name()}"];
        if ($argument->shortName() !== null) {
            $names[] = "-{$argument->shortName()}";
        }
        return $names;
    }

    public function get(string $arg): ?Argument
    {
        foreach ($this->arguments as $argument) {
            if (in_array($arg, $this->getNames($argument), true)) {
                return $argument;
            }
        }

        return null;
    }

    public function remove(Argument $argument): void
    {
        $index = array_search($argument, $this->arguments, true);
        if ($index !== false) {
            unset($this->arguments[$index]);
        }
    }
}
