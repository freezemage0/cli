<?php

namespace Freezemage\Cli;


abstract class Application implements CommandProviderInterface
{
    /** @var array<string, CommandInterface> */
    private array $commands = [];

    final public function addCommand(CommandInterface $command): self
    {
        $this->commands[$command->name()] = $command;
        return $this;
    }

    final public function getCommands(): array
    {
        return $this->commands;
    }

    public function run(Input $input = null, Output $output = null): never
    {
        $input ??= new Input($this->getGlobalArguments());
        $output ??= new Output();

        $command = $this->getCommand($input->getCommandName());
        if (empty($command)) {
            $output->error('No command specified');
            exit(ExitCode::FAILURE);
        }

        $code = $command->execute($input, $output);
        exit($code->value);
    }

    abstract public function getGlobalArguments(): ArgumentList;

    final public function getCommand(string $name): ?CommandInterface
    {
        return $this->commands[$name] ?? null;
    }
}