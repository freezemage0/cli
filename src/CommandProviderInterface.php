<?php

declare(strict_types=1);

namespace Freezemage\Cli;

interface CommandProviderInterface
{
    public function addCommand(CommandInterface $command): self;

    public function getCommand(string $name): ?CommandInterface;

    /**
     * @return array<string, CommandInterface>
     */
    public function getCommands(): array;
}
