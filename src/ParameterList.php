<?php

namespace Freezemage\Cli;

use Freezemage\Cli\Argument\Argument;

final class ParameterList
{
    /** @var array<string, Parameter> */
    private array $parameters = [];

    public function has(Argument $argument): bool
    {
        return isset($this->parameters[$argument->name()]);
    }

    public function getValue(string $name): string|int|float|bool|null
    {
        return $this->get($name)?->value;
    }

    public function get(string $name): ?Parameter
    {
        return $this->parameters[$name] ?? null;
    }

    public function insert(Parameter $parameter): void
    {
        $this->parameters[$parameter->name] = $parameter;
    }

    public function merge(ParameterList $parameterList): ParameterList
    {
        $mergedParameters = new ParameterList();
        $mergedParameters->parameters = $this->parameters;

        foreach ($parameterList->parameters as $parameter) {
            $mergedParameters->parameters[$parameter->name] = $parameter;
        }

        return $mergedParameters;
    }
}
