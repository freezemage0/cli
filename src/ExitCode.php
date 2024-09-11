<?php

declare(strict_types=1);

namespace Freezemage\Cli;

enum ExitCode: int
{
    case Ok = 0;
    case Failure = 1;
}
