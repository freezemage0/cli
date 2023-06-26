<?php

namespace Freezemage\Cli\Output;

enum Color: int
{
    case BLACK = 0;
    case RED = 1;
    case GREEN = 2;
    case YELLOW = 3;
    case BLUE = 4;
    case MAGENTA = 5;
    case CYAN = 6;
    case WHITE = 7;
    case DEFAULT = 9;
}
