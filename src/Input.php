<?php

namespace Freezemage\Cli;

use DomainException;
use Freezemage\Cli\Argument\Argument;
use Freezemage\Cli\Input\ComplexStrategy;
use Freezemage\Cli\Input\Interactive;
use Freezemage\Cli\Input\NonInteractive;
use Freezemage\Cli\Input\Strategy;

final class Input
{
    public function __construct(private readonly ArgumentList $globalArguments)
    {
    }

    public function getCommandName(): string
    {
        global $argv;
        return $argv[1] ?? 'help';
    }

    public function getParameter(Argument $argument): Parameter
    {
        $strategy = $this->getParameterParser();

        $parameterList = $strategy->getParameters(new ArgumentList($argument));
        return $parameterList->get($argument->name());
    }

    private function getParameterParser(): Strategy
    {
        if ($this->isNonInteractive()) {
            return new NonInteractive();
        }

        if ($this->isInteractive()) {
            return new Interactive();
        }

        $multiple = new ComplexStrategy();
        $multiple->add(new NonInteractive());
        $multiple->add(new Interactive());

        return $multiple;
    }

    public function isNonInteractive(): bool
    {
        global $argv;
        return in_array('--no-interaction', $argv, true);
    }

    public function isInteractive(): bool
    {
        global $argv;
        return in_array('--interactive', $argv, true);
    }

    public function getParameters(ArgumentList $argumentList): ParameterList
    {
        $parameterParser = $this->getParameterParser();

        $globalParameters = $parameterParser->getParameters($this->globalArguments);
        $parameters = $parameterParser->getParameters($argumentList);

        foreach ($argumentList as $argument) {
            if ($argument->isRequired() && !$parameters->has($argument)) {
                throw new DomainException("Missing required parameter.");
            }
        }

        return $globalParameters->merge($parameters);
    }
}