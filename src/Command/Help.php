<?php

declare(strict_types=1);

namespace Freezemage\Cli\Command;

use Freezemage\Cli\Argument\Choice;
use Freezemage\Cli\Argument\Describable;
use Freezemage\Cli\Argument\DescriptionService;
use Freezemage\Cli\Argument\Flag;
use Freezemage\Cli\Argument\Question;
use Freezemage\Cli\ArgumentList;
use Freezemage\Cli\CommandInterface;
use Freezemage\Cli\CommandProviderInterface;
use Freezemage\Cli\ExitCode;
use Freezemage\Cli\Input;
use Freezemage\Cli\Output;

class Help implements CommandInterface, DescriptionService
{
    public function __construct(
        private readonly CommandProviderInterface $commandProvider
    ) {
    }

    public function execute(Input $input, Output $output): ExitCode
    {
        $input->interactive = false;

        $parameters = $input->getParameters($this->argumentList());
        $commandName = $parameters->getValue('command') ?? 'list';

        if ($commandName !== 'list') {
            $command = $this->commandProvider->getCommand((string)$commandName);

            if (empty($command)) {
                $output->error("Command {$commandName} not found.");

                return ExitCode::Failure;
            }

            $output->info('Synopsis');
            $output->info("{$command->name()}: ", false);
            $output->write("{$command->description()}\n");

            $info = [];
            foreach ($command->argumentList() as $argument) {
                if ($argument instanceof Describable) {
                    $info[] = $argument->describe($this);
                }
            }

            $output->info('Arguments');
            if (empty($info)) {
                $info[] = 'No arguments';
            }
            $output->write(\implode("\n", $info));

            return ExitCode::Ok;
        }

        $output->write('List of available commands');
        foreach ($this->commandProvider->getCommands() as $command) {
            $output->info("{$command->name()}: ", false);
            $output->write($command->description());
        }

        return ExitCode::Ok;
    }

    public function argumentList(): ArgumentList
    {
        return new ArgumentList(
            new Question('command', 'Command to describe', 'list', 'c')
        );
    }

    public function name(): string
    {
        return 'help';
    }

    public function description(): string
    {
        return 'Displays help for supported commands or a list of commands if command is not specified.';
    }

    public function describeChoice(Choice $choice): string
    {
        $names = ["--{$choice->name}"];
        if (isset($choice->shortName)) {
            $names[] = "-{$choice->shortName}";
        }

        $names = \implode(', ', $names);
        $length = \count($choice->items);

        $description = "{$names} -- {$choice->question}. Must be in range [1, {$length}].";
        if (isset($choice->defaultItem)) {
            $description .= "Defaults to {$choice->defaultItem}.";
        } else {
            $description .= 'Required.';
        }

        return $description;
    }

    public function describeFlag(Flag $flag): string
    {
        $names = ["--{$flag->name}"];
        if (isset($flag->shortName)) {
            $names[] = "-{$flag->shortName}";
        }

        $names = \implode(', ', $names);
        $description = "{$names} -- {$flag->question}. ";
        if (isset($flag->defaultValue)) {
            $description .= ($flag->defaultValue ? 'Enabled' : 'Disabled') . ' by default.';
        } else {
            $description .= 'Required.';
        }

        return $description;
    }

    public function describeQuestion(Question $question): string
    {
        $names = ["--{$question->name}"];
        if (isset($question->shortName)) {
            $names[] = "-{$question->shortName}";
        }

        $names = \implode(', ', $names);
        $description = "{$names} -- {$question->question}. ";
        if (isset($question->defaultAnswer)) {
            $description .= "Defaults to '{$question->defaultAnswer}'.";
        } else {
            $description .= 'Required.';
        }

        return $description;
    }
}
