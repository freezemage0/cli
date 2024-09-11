<?php

declare(strict_types=1);

namespace Freezemage\Cli\Argument;

use Freezemage\Cli\Parameter;

interface Interactable
{
    public function interact(InteractionService $interactionService): Parameter;
}
