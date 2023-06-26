<?php

namespace Freezemage\Cli;

enum ArgumentType
{
    case FLAG;
    case CHOICE;
    case QUESTION;
}
