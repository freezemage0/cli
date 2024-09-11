<?php

declare(strict_types=1);

namespace Freezemage\Cli\Output;

final class Ansi implements PrinterStrategy
{
    public function render(
        string $message,
        Color $foreground = Color::Default,
        Color $background = null,
        Style $style = null
    ): void {
        $ansi = [
            isset($style) ? $style->value : 0,
            $foreground->value + 30,
            isset($background) ? $background->value + 40 : null,
        ];

        $ansi = \implode(';', \array_filter($ansi));

        echo "\033[{$ansi}m{$message}\033[0m";
    }
}
