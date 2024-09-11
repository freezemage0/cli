<?php

declare(strict_types=1);

namespace Freezemage\Cli\Internal;

class ArgvStorage
{
    /** @var array<int, string> */
    private static ?array $argv = null;

    public function get(int $position): ?string
    {
        $arguments = $this->argv();
        return $arguments[$position] ?? null;
    }

    /**
     * @return array<int, string>
     */
    private function argv(): array
    {
        global $argv;
        return ArgvStorage::$argv ??= $argv;
    }

    public function has(int $position): bool
    {
        $arguments = $this->argv();
        return isset($arguments[$position]);
    }

    public function contains(string $value): bool
    {
        return \in_array($value, $this->argv(), true);
    }

    public function findIndex(string $value): ?int
    {
        return \array_search($value, $this->argv(), true) ?: null;
    }

    public function findLastIndex(string $value): ?int
    {
        $arguments = $this->argv();
        for ($i = \count($arguments); $i > 0; $i -= 1) {
            $argument = $arguments[$i - 1];

            if ($argument === $value) {
                return $i;
            }
        }

        return null;
    }

    public function find(callable $finder): ?int
    {
        foreach ($this->argv() as $index => $argument) {
            if ($finder($argument) === true) {
                return $index;
            }
        }

        return null;
    }

    public function map(callable $mapper): array
    {
        return \array_map($mapper, $this->argv());
    }

    public function count(): int
    {
        return \count($this->argv());
    }
}
