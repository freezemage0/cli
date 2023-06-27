<?php

namespace Freezemage\Cli;

use Freezemage\Cli\Output\Ansi;
use Freezemage\Cli\Output\Color;
use Freezemage\Cli\Output\NoAnsi;
use Freezemage\Cli\Output\PrinterStrategy;
use Freezemage\Cli\Output\Style;

final class Output
{
    public function info(string $message, bool $newline = true): void
    {
        $this->write($message, Color::YELLOW, newline: $newline);
    }

    public function write(
        string $message,
        Color $foreground = Color::DEFAULT,
        Color $background = null,
        Style $style = null,
        bool $newline = true
    ): void {
        $this->getPrinterStrategy()->render($message, $foreground, $background, $style);
        echo $newline ? "\n" : '';
    }

    private function getPrinterStrategy(): PrinterStrategy
    {
        global $argv;

        return in_array('--no-ansi', $argv, true) ? new NoAnsi() : new Ansi();
    }

    public function error(string $message, bool $newline = true): void
    {
        $this->write($message, Color::RED, style: Style::BOLD, newline: $newline);
    }

    public function success(string $message, bool $newline = true): void
    {
        $this->write($message, Color::GREEN, style: Style::BOLD, newline: $newline);
    }
}