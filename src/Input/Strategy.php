<?php

declare(strict_types=1);

namespace Freezemage\Cli\Input;

use Freezemage\Cli\ArgumentList;
use Freezemage\Cli\ParameterList;

interface Strategy
{
    public function getParameters(ArgumentList $argumentList): ParameterList;
}
