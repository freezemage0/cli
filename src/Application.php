<?php


namespace Freezemage\Cli;


use Freezemage\Cli\Command\Help;
use Freezemage\Cli\Input\Strategy\Factory;
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
        $commands = $this->commands;

        $defaultCommand = $this->getDefaultCommand();
        if (isset($defaultCommand)) {
            $commands[$defaultCommand->name()] = $defaultCommand;
        }
        return $commands;
    }

    protected function getDefaultCommand(): ?CommandInterface
    {
        return new Help($this);
    }

    public function run(Input $input = null, Output $output = null): void
    {
        $output ??= new Output();
        $input ??= new Input($this->getGlobalArguments(), new Factory($output));

        $name = $input->getCommandName();
        $command = !empty($name) ? $this->getCommand($name) : $this->getDefaultCommand();

        if (empty($command)) {
            $output->error('No command specified');
            $this->finalizer->finalize(ExitCode::FAILURE);
        } else {
            $code = $command->execute($input, $output);
            $this->finalizer->finalize($code);
        }
    }

    protected function getGlobalArguments(): ArgumentList
    {
        return new ArgumentList();
    }

    final public function getCommand(string $name): ?CommandInterface
    {
        return $this->commands[$name] ?? $this->getDefaultCommand();
    }
}
