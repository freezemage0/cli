<?php

namespace Freezemage\Cli\Argument;

interface Describable
{
    public function describe(DescriptionService $descriptionService): string;
}