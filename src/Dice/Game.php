<?php

declare(strict_types=1);

namespace jesr20\Dice;

use function Mos\Functions\redirectTo;
use function Mos\Functions\renderView;
use function Mos\Functions\sendResponse;
use function Mos\Functions\url;
use jesr20\Dice\Dice;
use jesr20\Dice\GraphicalDice;

$numberOfDices = $_SESSION["numberOfDices"] ?? null;

/**
 * Class Dice.
 */
class Game
{
    public $sum = 0;
    public $sumCpu = 0;
    public $die;
    public $dieCpu;
    public $diceGraphic;
    public $stringNumberOfDices = "";
    public $player = 0;
    public $cpu = 0;
    public $string = "";

    public function requestNumberOfDices(): void
    {
        $data = [
            "header" => "Game21",
            "action" => url("/game/start21process"),
            "reset" => url("/session/21")
        ];

        $body = renderView("layout/21/start21.php", $data);
        sendResponse($body);
    }

    public function stringNumberOfDices($numberOfDices)
    {
        if ($numberOfDices == 1) {
            $this->stringNumberOfDices .= "Playing with one dice. ";
        } else if ($numberOfDices == 2) {
            $this->stringNumberOfDices .= "Playing with two dices. ";
        }
        return $this->stringNumberOfDices;
    }

    public function startGame($numberOfDices)
    {
        $this->sum = 0;
        $this->player = 0;
        $this->cpu = 0;

        $data = [
            "header" => "Game21",
            "message2" => $this->stringNumberOfDices($numberOfDices),
            "message3" => "Sum: ",
            "action" => url("/game/play21"),
            "sum" => $this->sum,
        ];

        $body = renderView("layout/21/play21.php", $data);
        sendResponse($body);
        return $this->sum;
    }

    public function rollDice($numberOfDices)
    {
        $this->die = new GraphicalDice();
        $diceGraphic = $this->die->dices;
        $this->die->roll();
        $this->sum += $this->die->getLastRoll();
        $this->string = $diceGraphic[$this->die->getLastRoll()];
        if ($numberOfDices == 2) {
            $this->die->roll();
            $this->string .= $diceGraphic[$this->die->getLastRoll()];
            $this->sum += $this->die->getLastRoll();
        }
    }

    public function rollDiceCpu($numberOfDices)
    {
        $this->dieCpu = new Dice();
        $this->sumCpu = 0;

        while (1) {
            if ($this->sumCpu >= $this->sum) {
                break;
            }
            $this->dieCpu->roll();
            $this->sumCpu += $this->dieCpu->getLastRoll();
            if ($numberOfDices == 2) {
                $this->dieCpu->roll();
                $this->sumCpu += $this->dieCpu->getLastRoll();
            }
        }
    }

    public function rollGame(
        $move,
        $numberOfDices,
        $rolledSum,
        $playerWins,
        $cpuWins
    ) {
        $this->sum = $rolledSum;
        $this->player = $playerWins;
        $this->cpu = $cpuWins;

        if ($move == "Roll") {
            $this->rollDice($numberOfDices);

            if ($this->sum < 21) {
                $data = [
                    "header" => "Game21",
                    "message1" => $this->string,
                    "message2" => $this->stringNumberOfDices($numberOfDices),
                    "message3" => "Sum: ",
                    "action" => url("/game/play21"),
                    "sum" => $this->sum,
                ];
                $body = renderView("layout/21/play21.php", $data);
                sendResponse($body);
            } else if ($this->sum == 21) {
                $this->player += 1;
                $data = [
                    "header" => "Game21",
                    "message" => "You win.",
                    "output" => "You rolled 21.",
                    "action" => url("/game"),
                    "player" => $this->player,
                    "cpu" => $this->cpu
                ];

                $body = renderView("layout/21/result21.php", $data);
                sendResponse($body);
            } else if ($this->sum > 21) {
                $this->cpu += 1;
                $data = [
                    "header" => "Game21",
                    "message" => "You lose.",
                    "output" => "You rolled more than 21.",
                    "action" => url("/game"),
                    "player" => $this->player,
                    "cpu" => $this->cpu,
                ];


                $body = renderView("layout/21/result21.php", $data);
                sendResponse($body);
            }
        } else if ($move == "Stop") {
            $this->rollDiceCpu($numberOfDices);

            if ($this->sumCpu >= $this->sum && $this->sumCpu <= 21) {
                $this->cpu += 1;
                $data = [
                    "header" => "Game21",
                    "message" => "Computer win.",
                    "totals" => "Computer rolled " . strval($this->sumCpu) .
                    ", you rolled " . strval($this->sum),
                    "action" => url("/game"),
                    "player" => $this->player,
                    "cpu" => $this->cpu,
                ];
            } else {
                $this->player += 1;
                $data = [
                    "header" => "Game21",
                    "message" => "You win.",
                    "totals" => "Computer rolled " . strval($this->sumCpu) .
                    ", you rolled " . strval($this->sum),
                    "action" => url("/game"),
                    "player" => $this->player,
                    "cpu" => $this->cpu,
                ];
            }

            $body = renderView("layout/21/result21.php", $data);
            sendResponse($body);
        }
        return $this->sum;
    }
}
