<?php

declare(strict_types=1);

namespace Freezemage\Cli;

final class Parameter
{
    /**
     * @param string $name
     * @param scalar|null $value
     */
    public function __construct(
        public readonly string $name,
        public readonly mixed $value
    ) {
    }
}
