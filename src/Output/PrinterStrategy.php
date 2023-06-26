<?php

namespace Freezemage\Cli\Output;

interface PrinterStrategy
{
    public function render(
        string $message,
        Color $foreground = Color::DEFAULT,
        Color $background = null,
        Style $style = null
    ): void;
}