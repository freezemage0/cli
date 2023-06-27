<?php

namespace Freezemage\Cli;


use Freezemage\Cli\Command\Help;

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

    final public function getCommand(string $name): ?CommandInterface
    {
        return $this->commands[$name] ?? $this->getDefaultCommand();
    }

    protected function getDefaultCommand(): ?CommandInterface
    {
        return new Help($this);
    }

    protected function getGlobalArguments(): ArgumentList {
        return new ArgumentList();
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
}