<?php

namespace Freezemage\Cli\Input;

use Freezemage\Cli\ArgumentList;
use Freezemage\Cli\ParameterList;

final class Multiple implements Strategy
{
    /** @var list<Strategy> */
    private array $parserStrategies = [];

    public function add(Strategy $strategy): void
    {
        $this->parserStrategies[] = $strategy;
    }

    public function getParameters(ArgumentList $argumentList): ParameterList
    {
        $parameterList = new ParameterList();
        foreach ($this->parserStrategies as $parserStrategy) {
            $parsedParameters = $parserStrategy->getParameters($argumentList);

            $parameterList = $parameterList->merge($parsedParameters);

            foreach ($argumentList as $argument) {
                if ($parsedParameters->has($argument)) {
                    $argumentList->remove($argument);
                }
            }
        }

        return $parameterList;
    }
}