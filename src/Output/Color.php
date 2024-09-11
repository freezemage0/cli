<?php

declare(strict_types=1);

namespace Freezemage\Cli\Output;

enum Color: int
{
    case Black = 0;
    case Red = 1;
    case Green = 2;
    case Yellow = 3;
    case Blue = 4;
    case Magenta = 5;
    case Cyan = 6;
    case White = 7;
    case Default = 9;
}
