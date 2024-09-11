<?php

declare(strict_types=1);

namespace Freezemage\Cli\Input\Strategy;

use Freezemage\Cli\Argument\Choice;
use Freezemage\Cli\Argument\Flag;
use Freezemage\Cli\Argument\Interactable;
use Freezemage\Cli\Argument\InteractionService;
use Freezemage\Cli\Argument\Question;
use Freezemage\Cli\ArgumentList;
use Freezemage\Cli\Input\Strategy;
use Freezemage\Cli\Output;
use Freezemage\Cli\Parameter;
use Freezemage\Cli\ParameterList;
use InvalidArgumentException;

final class Interactive implements Strategy, InteractionService
{
    public function __construct(
        private readonly Output $output
    ) {
    }

    public function getParameters(ArgumentList $argumentList): ParameterList
    {
        $parameters = new ParameterList();
        foreach ($argumentList as $argument) {
            if (!($argument instanceof Interactable)) {
                throw new InvalidArgumentException('Argument is non-interactable.');
            }
            $parameters->insert($argument->interact($this));
        }
        return $parameters;
    }

    public function interactQuestion(Question $question): Parameter
    {
        if (isset($question->defaultAnswer)) {
            $this->output->write("{$question->question} [default: {$question->defaultAnswer}]:");
        } else {
            $this->output->write("{$question->question}:");
        }

        $response = \trim(\readline());
        if (empty($response)) {
            if (isset($question->defaultAnswer)) {
                return new Parameter($question->name, $question->defaultAnswer);
            }

            return $this->interactQuestion($question);
        }

        return new Parameter($question->name, $response);
    }

    public function interactFlag(Flag $flag): Parameter
    {
        $choices = ['y', 'n'];
        if (isset($flag->defaultValue)) {
            $position = (int)(!$flag->defaultValue); // true -> 0, false -> 1
            $choices[$position] = \strtoupper($choices[$position]);
        }

        $this->output->write("{$flag->question} [{$choices[0]}/{$choices[1]}]:");

        $input = \strtolower(\readline());
        if (!\in_array($input, $choices)) {
            if (isset($flag->defaultValue)) {
                return new Parameter($flag->name, $flag->defaultValue);
            }

            $this->output->error("Answer must be one of: [y, n]");
            return $this->interactFlag($flag);
        }

        return new Parameter($flag->name, $input === 'y');
    }

    public function interactChoice(Choice $choice): Parameter
    {
        $choices = $choice->items;

        $this->output->write("{$choice->question}:");
        for ($i = 1, $length = \count($choices); $i <= $length; $i += 1) {
            $this->output->write("{$i}. {$choices[$i - 1]}");
        }

        $item = (int)\readline();
        if (!$choice->isSuitableAnswer($item)) {
            if (isset($choice->defaultItem)) {
                return new Parameter($choice->name, $choice->defaultItem);
            }

            $this->output->error("Answer must be in range [1, {$length}]");
            return $this->interactChoice($choice);
        }

        return new Parameter($choice->name, $item);
    }
}
