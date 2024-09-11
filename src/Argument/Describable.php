<?php

declare(strict_types=1);

namespace Freezemage\Cli\Argument;

interface Describable
{
    public function describe(DescriptionService $descriptionService): string;
}
