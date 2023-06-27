<?php

namespace Freezemage\Cli\Output;

final class Ansi implements PrinterStrategy
{
    public function render(
        string $message,
        Color $foreground = Color::DEFAULT,
        Color $background = null,
        Style $style = null
    ): void {
        $ansi = [
            $style->value ?? 0,
            isset($foreground) ? $foreground->value + 30 : null,
            isset($background) ? $background->value + 40 : null,
        ];

        $ansi = implode(';', array_filter($ansi));

        echo "\033[{$ansi}m{$message}\033[0m";
    }
}