<?php

declare(strict_types=1);

namespace Freezemage\Cli;

enum ArgumentType
{
    case Flag;
    case Choice;
    case Question;
}
