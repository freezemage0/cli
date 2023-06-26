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
            if ("--{$argument->name}" == $arg || "-{$argument->shortName}" == $arg) {
                return true;
            }
        }

        return false;
    }

    public function get(string $arg): ?Argument
    {
        foreach ($this->arguments as $argument) {
            if ("--{$argument->name}" == $arg || "-{$argument->shortName}" == $arg) {
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