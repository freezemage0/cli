<?php

namespace Freezemage\Cli;

use DomainException;
use Freezemage\Cli\Argument\Argument;
use Freezemage\Cli\Input\ComplexStrategy;
use Freezemage\Cli\Input\Interactive;
use Freezemage\Cli\Input\NonInteractive;
use Freezemage\Cli\Input\Strategy;
use Freezemage\Cli\Internal\ArgvStorage;

final class Input
{
    public function __construct(
        private readonly ArgumentList $globalArguments,
        private readonly string $defaultCommand = 'help',
        private readonly ArgvStorage $argvStorage = new ArgvStorage(),
        public ?bool $interactive = null
    ) {
    }

    public function getCommandName(): string
    {
        if ($this->argvStorage->count() < 2) {
            return $this->defaultCommand;
        }

        return $this->argvStorage->get(1) ?? $this->defaultCommand;
    }

    public function getParameter(Argument $argument): ?Parameter
    {
        $strategy = $this->getParameterParser();

        $parameterList = $strategy->getParameters(new ArgumentList($argument));
        return $parameterList->get($argument->name());
    }

    private function getParameterParser(): Strategy
    {
        if ($this->isNonInteractive()) {
            return new NonInteractive($this->argvStorage);
        }

        if ($this->isInteractive()) {
            return new Interactive();
        }

        $multiple = new ComplexStrategy();
        $multiple->add(new NonInteractive($this->argvStorage));
        $multiple->add(new Interactive());

        return $multiple;
    }

    public function isNonInteractive(): bool
    {
        if (isset($this->interactive)) {
            return !$this->interactive;
        }

        return $this->argvStorage->contains('--no-interaction');
    }

    public function isInteractive(): bool
    {
        if (isset($this->interactive)) {
            return $this->interactive;
        }

        return $this->argvStorage->contains('--interactive');
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
