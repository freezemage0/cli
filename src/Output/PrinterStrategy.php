<?php

declare(strict_types=1);

namespace Freezemage\Cli\Output;

interface PrinterStrategy
{
    public function render(
        string $message,
        Color $foreground = Color::Default,
        Color $background = null,
        Style $style = null
    ): void;
}
