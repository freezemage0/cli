<?php

namespace Freezemage\Cli\Argument;

interface DescriptionService
{
    public function describeChoice(Choice $choice): string;

    public function describeFlag(Flag $flag): string;

    public function describeQuestion(Question $question): string;
}