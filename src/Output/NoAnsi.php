<?php

declare(strict_types=1);

namespace Freezemage\Cli\Output;

final class NoAnsi implements PrinterStrategy
{
    public function render(
        string $message,
        Color $foreground = Color::Default,
        Color $background = null,
        Style $style = null
    ): void {
        echo $message;
    }
}
