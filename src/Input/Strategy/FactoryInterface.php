<?php

declare(strict_types=1);

namespace Freezemage\Cli\Input\Strategy;

interface FactoryInterface
{
    public function createInteractive(): Interactive;

    public function createNonInteractive(): NonInteractive;
}
