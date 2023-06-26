<?php

namespace Freezemage\Cli;

interface CommandInterface
{
    public function name(): string;

    public function description(): string;

    public function argumentList(): ArgumentList;

    public function execute(Input $input, Output $output): ExitCode;
}