<?php

namespace Freezemage\Cli\Input;

use Freezemage\Cli\ArgumentList;
use Freezemage\Cli\ArgumentType;
use Freezemage\Cli\Parameter;
use Freezemage\Cli\ParameterList;

class NonInteractive implements Strategy
{
    public function getParameters(ArgumentList $argumentList): ParameterList
    {
        global $argv;

        $arguments = $argv; // loose ref;
        $parameters = new ParameterList();

        while (!empty($arguments)) {
            $arg = array_shift($arguments);

            $argument = $argumentList->get($arg);
            if (empty($argument)) {
                continue;
            }

            if ($argument->type() === ArgumentType::FLAG) {
                $value = true;
            } else {
                $value = array_shift($arguments);
            }

            $parameters->insert(new Parameter($argument->name(), $value));
        }

        return $parameters;
    }
}