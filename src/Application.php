<?php

namespace Freezemage\Cli;


use Freezemage\Cli\Argument\Question;

final class Application implements CommandProviderInterface
{
    /** @var array<string, CommandInterface> */
    private array $commands = [];

    public function addCommand(CommandInterface $command): self
    {
        $this->commands[$command->name()] = $command;
        return $this;
    }

    public function getCommand(string $name): ?CommandInterface
    {
        return $this->commands[$name] ?? null;
    }

    public function getCommands(): array
    {
        return $this->commands;
    }

    public function getGlobalArguments(): ArgumentList
    {
        return new ArgumentList(
            new Question('project-root', 'Project root', getcwd(), 'r')
        );
    }

    public function run(Input $input = null, Output $output = null): never
    {
        $input ??= new Input($this->getGlobalArguments());
        $output ??= new Output();

        $command = $this->getCommand($input->getCommandName());
        $code = $command->execute($input, $output);

        exit($code->value);
    }
}