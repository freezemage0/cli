<?php

namespace Freezemage\Cli;

enum ExitCode: int
{
    case OK = 0;
    case FAILURE = 1;
}