<?php


namespace Freezemage\Cli\Internal;

use Freezemage\Cli\ExitCode;


interface Finalizer
{
    public function finalize(ExitCode $code): void;
}
