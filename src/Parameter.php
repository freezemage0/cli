<?php

namespace Freezemage\Cli;

final class Parameter
{
    public function __construct(
        public readonly string $name,
        public readonly string $value
    ) {
    }
}