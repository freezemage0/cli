<?php

namespace Freezemage\Cli\Output;

final class NoAnsi implements PrinterStrategy
{
    public function render(
        string $message,
        Color $foreground = Color::DEFAULT,
        Color $background = null,
        Style $style = null
    ): void {
        echo $message . "\n";
    }
}