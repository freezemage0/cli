<?php

namespace Freezemage\Cli\Input;

use DomainException;
use Freezemage\Cli\Argument\Choice;
use Freezemage\Cli\Argument\Flag;
use Freezemage\Cli\Argument\Question;
use Freezemage\Cli\ArgumentList;
use Freezemage\Cli\ArgumentType;
use Freezemage\Cli\Parameter;
use Freezemage\Cli\ParameterList;

class Interactive implements Strategy
{
    private int $retries = 0;
    private int $retryLimit = 5;

    public function getParameters(ArgumentList $argumentList): ParameterList
    {
        $parameters = new ParameterList();
        foreach ($argumentList as $argument) {
            $value = match ($argument->type()) {
                ArgumentType::QUESTION => $this->ask($argument),
                ArgumentType::FLAG => $this->confirm($argument),
                ArgumentType::CHOICE => $this->choice($argument),
            };
            $parameters->insert(new Parameter($argument->name, $value));
        }
        return $parameters;
    }

    public function ask(Question $question): string
    {
        if (isset($question->defaultAnswer)) {
            echo "{$question->question} [default: {$question->defaultAnswer}]: ";
        } else {
            echo "{$question->question}: ";
        }

        $response = trim(readline());
        if (empty($response)) {
            if (isset($question->defaultAnswer)) {
                return $question->defaultAnswer;
            }
            return $this->ask($question);
        }

        return $response;
    }

    public function confirm(Flag $flag): bool
    {
        $this->validateRetries();

        $choices = ['y', 'n'];
        if (isset($flag->defaultValue)) {
            $position = (int)(!$flag->defaultValue); // true -> 0, false -> 1
            $choices[$position] = strtoupper($choices[$position]);
        }

        echo "{$flag->question} [{$choices[0]}/{$choices[1]}]: ";
        $input = strtolower(readline());
        if (!in_array($input, $choices)) {
            if (isset($flag->defaultValue)) {
                return $flag->defaultValue;
            }

            echo "Answer must be one of: [y, n]";
            $this->retries += 1;
            return $this->confirm($flag);
        }

        $this->retries = 0;
        return $input === 'y';
    }

    private function validateRetries(): void
    {
        if ($this->retries > $this->retryLimit) {
            throw new DomainException('Retry limit exceeded.');
        }
    }

    public function choice(Choice $choice): string
    {
        $choices = $choice->items;
        $length = count($choices);

        echo "{$choice->question}: \n";

        for ($i = 1; $i <= $length; $i += 1) {
            echo "{$i}. {$choices[$i - 1]}\n";
        }

        $item = (int)readline();
        if ($item < 1 || $item > $length) {
            if (isset($default)) {
                $this->retries = 0;
                return $default;
            }

            echo "Answer must be in range [1, {$length}]\n";
            $this->retries += 1;
            return $this->choice($choice);
        }

        $this->retries = 0;
        return $item;
    }
}