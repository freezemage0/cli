<?php

declare(strict_types=1);

namespace Freezemage\Cli;

use DomainException;
use Freezemage\Cli\Argument\Argument;
use Freezemage\Cli\Input\Strategy;
use Freezemage\Cli\Input\Strategy\Complex;
use Freezemage\Cli\Input\Strategy\FactoryInterface as StrategyFactory;
use Freezemage\Cli\Internal\ArgvStorage;

final class Input
{
    public function __construct(
            private readonly ArgumentList $globalArguments,
            private readonly StrategyFactory $strategyFactory,
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
            return $this->strategyFactory->createNonInteractive();
        }

        if ($this->isInteractive()) {
            return $this->strategyFactory->createInteractive();
        }

        $complexStrategy = new Complex();
        $complexStrategy->add($this->strategyFactory->createNonInteractive());
        $complexStrategy->add($this->strategyFactory->createInteractive());

        return $complexStrategy;
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
