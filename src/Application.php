<?php


namespace Freezemage\Cli;


use Freezemage\Cli\Command\Help;
use Freezemage\Cli\Internal\DefaultFinalizer;
use Freezemage\Cli\Internal\Finalizer;


abstract class Application implements CommandProviderInterface
{
    /** @var array<string, CommandInterface> */
    private array $commands = [];

    public function __construct(private readonly Finalizer $finalizer = new DefaultFinalizer())
    {
    }

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

    protected function getGlobalArguments(): ArgumentList
    {
        return new ArgumentList();
    }

    public function run(Input $input = null, Output $output = null): void
    {
        $input ??= new Input($this->getGlobalArguments());
        $output ??= new Output();

        $command = !empty($commandName) ? $this->getCommand($input->getCommandName()) : $this->getDefaultCommand();

        if (empty($command)) {
            $output->error('No command specified');
            $this->finalizer->finalize(ExitCode::FAILURE);
        }

        $code = $command->execute($input, $output);
        $this->finalizer->finalize($code);
    }
}
