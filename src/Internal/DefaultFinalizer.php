<?php

declare(strict_types=1);

namespace Freezemage\Cli\Internal;

use Freezemage\Cli\ExitCode;

final class DefaultFinalizer implements Finalizer
{
    public function finalize(ExitCode $code): never
    {
        exit($code->value);
    }
}
