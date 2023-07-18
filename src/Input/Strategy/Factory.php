<?php


namespace Freezemage\Cli\Input\Strategy;


use Freezemage\Cli\Internal\ArgvStorage;
use Freezemage\Cli\Output;


class Factory implements FactoryInterface
{
    public function __construct(
            private readonly Output $output,
            private readonly ArgvStorage $argvStorage = new ArgvStorage()
    ) {
    }

    public function createInteractive(): Interactive
    {
        return new Interactive($this->output);
    }

    public function createNonInteractive(): NonInteractive
    {
        return new NonInteractive($this->argvStorage);
    }
}
