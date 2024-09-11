<?php

declare(strict_types=1);

namespace Freezemage\Cli\Output;

enum Style: int
{
    case Bold = 1;
    case Dim = 2;
    case Italic = 3;
    case Underline = 4;
    case Blinking = 5;
    case Inverse = 7;
    case Invisible = 8;
    case Strikethrough = 9;
}
