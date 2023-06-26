<?php

namespace Freezemage\Cli\Output;

enum Style: int
{
    case BOLD = 1;
    case DIM = 2;
    case ITALIC = 3;
    case UNDERLINE = 4;
    case BLINKING = 5;
    case INVERSE = 7;
    case INVISIBLE = 8;
    case STRIKETHROUGH = 9;
}