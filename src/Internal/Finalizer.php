<?php

declare(strict_types=1);

namespace Freezemage\Cli\Internal;

use Freezemage\Cli\ExitCode;

interface Finalizer
{
    public function finalize(ExitCode $code): void;
}
