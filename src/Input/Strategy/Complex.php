<?php

declare(strict_types=1);

namespace Freezemage\Cli\Input\Strategy;

use Freezemage\Cli\ArgumentList;
use Freezemage\Cli\Input\Strategy;
use Freezemage\Cli\ParameterList;

final class Complex implements Strategy
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
