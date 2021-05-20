<?php

declare(strict_types=1);

namespace jesr20\Dice;

// use function Mos\Functions\{
//     destroySession,
//     redirectTo,
//     renderView,
//     renderTwigView,
//     sendResponse,
//     url
// };

/**
 * Class Dice.
 */
class DiceHand
{
    private array $dices;
    public int $sum = 0;
    public int $numberOfDices;

    public function __construct(
        $numberOfDices = 4
    )
    {
        $this->numberOfDices = $numberOfDices - 1;
        for ($i = 0; $i <= $this->numberOfDices; $i++) {
            $this->dices[$i] = new Dice();
        }
    }

    public function roll(): void
    {
        $len = count($this->dices);

        $this->sum = 0;
        for ($i = 0; $i <= $this->numberOfDices; $i++) {
            $this->sum += $this->dices[$i]->roll();
        }
    }

    public function getLastRoll(): string
    {
        $res = "";
        for ($i = 0; $i <= $this->numberOfDices; $i++) {
            $res .= $this->dices[$i]->getLastRoll() . ", ";
        }

        return $res . " = " . $this->sum;
    }
}
