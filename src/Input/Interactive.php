<?php

namespace Freezemage\Cli\Input;

use DomainException;
use Freezemage\Cli\Argument\Choice;
use Freezemage\Cli\Argument\Flag;
use Freezemage\Cli\Argument\Interactable;
use Freezemage\Cli\Argument\InteractionService;
use Freezemage\Cli\Argument\Question;
use Freezemage\Cli\ArgumentList;
use Freezemage\Cli\ArgumentType;
use Freezemage\Cli\Parameter;
use Freezemage\Cli\ParameterList;
use InvalidArgumentException;


class Interactive implements Strategy, InteractionService
{
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
            echo "{$question->question} [default: {$question->defaultAnswer}]: ";
        } else {
            echo "{$question->question}: ";
        }

        $response = trim(readline());
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
            $choices[$position] = strtoupper($choices[$position]);
        }

        echo "{$flag->question} [{$choices[0]}/{$choices[1]}]: ";
        $input = strtolower(readline());
        if (!in_array($input, $choices)) {
            if (isset($flag->defaultValue)) {
                return new Parameter($flag->name, $flag->defaultValue);
            }

            echo "Answer must be one of: [y, n]";
            return $this->interactFlag($flag);
        }

        return new Parameter($flag->name, $input === 'y');
    }

    public function interactChoice(Choice $choice): Parameter
    {
        $choices = $choice->items;
        $length = count($choices);

        echo "{$choice->question}: \n";

        for ($i = 1; $i <= $length; $i += 1) {
            echo "{$i}. {$choices[$i - 1]}\n";
        }

        $item = (int)readline();
        if (!$choice->isSuitableAnswer($item)) {
            if (isset($choice->defaultItem)) {
                return new Parameter($choice->name, $choice->defaultItem);
            }

            echo "Answer must be in range [1, {$length}]\n";
            return $this->interactChoice($choice);
        }

        return new Parameter($choice->name, $item);
    }
}
