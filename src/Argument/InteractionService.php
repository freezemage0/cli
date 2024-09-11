<?php

declare(strict_types=1);

namespace Freezemage\Cli\Argument;

use Freezemage\Cli\Parameter;

interface InteractionService
{
    public function interactQuestion(Question $question): Parameter;

    public function interactFlag(Flag $flag): Parameter;

    public function interactChoice(Choice $choice): Parameter;
}
