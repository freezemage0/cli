<?php

namespace Freezemage\Cli\Input;

use Freezemage\Cli\ArgumentList;
use Freezemage\Cli\ArgumentType;
use Freezemage\Cli\Internal\ArgvStorage;
use Freezemage\Cli\Parameter;
use Freezemage\Cli\ParameterList;

class NonInteractive implements Strategy
{

    public function __construct(private readonly ArgvStorage $storage)
    {
    }

    public function getParameters(ArgumentList $argumentList): ParameterList
    {
        $parameters = new ParameterList();

        for ($i = 0, $length = $this->storage->count(); $i < $length; $i += 1) {
            $arg = $this->storage->get($i);

            $argument = $argumentList->get($arg);
            if (empty($argument)) {
                continue;
            }

            if ($argument->type() === ArgumentType::FLAG) {
                $value = true;
            } else {
                $i += 1;
                $value = $this->storage->get($i);
            }

            $parameters->insert(new Parameter($argument->name(), $value));
        }

        return $parameters;
    }
}