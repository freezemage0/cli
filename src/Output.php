<?php

declare(strict_types=1);

namespace Freezemage\Cli;

use Freezemage\Cli\Internal\ArgvStorage;
use Freezemage\Cli\Output\Ansi;
use Freezemage\Cli\Output\Color;
use Freezemage\Cli\Output\NoAnsi;
use Freezemage\Cli\Output\PrinterStrategy;
use Freezemage\Cli\Output\Style;

final class Output
{
    public function __construct(private readonly ArgvStorage $argvStorage = new ArgvStorage())
    {
    }

    public function write(
            string $message,
            Color $foreground = Color::Default,
            Color $background = null,
            Style $style = null,
            bool $newline = true
    ): void {
        $this->getPrinterStrategy()->render($message, $foreground, $background, $style);
        echo $newline ? "\n" : '';
    }

    public function info(string $message, bool $newline = true): void
    {
        $this->write($message, Color::Yellow, newline: $newline);
    }

    private function getPrinterStrategy(): PrinterStrategy
    {
        return $this->argvStorage->contains('--no-ansi') ? new NoAnsi() : new Ansi();
    }

    public function error(string $message, bool $newline = true): void
    {
        $this->write($message, Color::Red, style: Style::Bold, newline: $newline);
    }

    public function success(string $message, bool $newline = true): void
    {
        $this->write($message, Color::Green, style: Style::Bold, newline: $newline);
    }
}
