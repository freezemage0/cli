<?php


namespace Freezemage\Cli\Internal;

use Freezemage\Cli\ExitCode;
use PHPUnit\Framework\Attributes\IgnoreClassForCodeCoverage;


#[IgnoreClassForCodeCoverage(DefaultFinalizer::class)]
final class DefaultFinalizer implements Finalizer
{
    public function finalize(ExitCode $code): never
    {
        exit($code->value);
    }
}
