<?php

declare(strict_types=1);

namespace jesr20\Dice;

use function Mos\Functions\{
    redirectTo,
    renderView,
    sendResponse,
    url
};

use jesr20\Dice\Dice;
use jesr20\Dice\GraphicalDice;

$numberOfDices = $_SESSION["numberOfDices"] ?? null;

/**
 * Class Dice.
 */
class Game
{
    public int $sum = 0;

    public int $sumCpu = 0;

    public object $die;

    public object $dieCpu;

    public static $diceGraphic;

    public static $StringNumberOfDices;

    public int $player = 0;

    public int $cpu = 0;

    public string $string = "";

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

    public function StringNumberOfDices($numberOfDices) {
        if ($numberOfDices == 1) {
            Game::$StringNumberOfDices .= "Playing with one dice. ";
        } else if ($numberOfDices == 2){
            Game::$StringNumberOfDices .= "Playing with two dices. ";
        }
        return Game::$StringNumberOfDices;
    }

    public function startGame($numberOfDices) {
        $this->sum = 0;
        $this->player = 0;
        $this->cpu = 0;

        $data = [
            "header" => "Game21",
            "message2" => Game::StringNumberOfDices($numberOfDices),
            "message3" => "Sum: ",
            "action" => url("/game/play21"),
            "sum" => $this->sum,
        ];

        $body = renderView("layout/21/play21.php", $data);
        sendResponse($body);
        return $this->sum;
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

            if ($this->sum < 21) {
                $data = [
                    "header" => "Game21",
                    "message1" => $this->string,
                    "message2" => Game::StringNumberOfDices($numberOfDices),
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
                    "message" => "Congratulations! You rolled 21, you win.",
                    "output" => "Oh. My. God.",
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
                    "message" => "You rolled more than 21, you lose",
                    "output" => "Oh. My. God.",
                    "action" => url("/game"),
                    "player" => $this->player,
                    "cpu" => $this->cpu,
                ];


                $body = renderView("layout/21/result21.php", $data);
                sendResponse($body);
            }
        } else if ($move == "Stop"){
            $this->dieCpu = new Dice();
            $this->sumCpu = 0;

            for ($i = 0; ; $i++) {
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

            if ($this->sumCpu >= $this->sum && $this->sumCpu <= 21) {
                $this->cpu += 1;
                $data = [
                    "header" => "Computer won.",
                    "message" => "Result",
                    "totals" => "Computer rolled " . strval($this->sumCpu) .
                    ", you rolled " . strval($this->sum),
                    "output" => "Oh. My. God.",
                    "action" => url("/game"),
                    "player" => $this->player,
                    "cpu" => $this->cpu,
                ];

            } else {
                $this->player += 1;
                $data = [
                    "header" => "You won.",
                    "message" => "Result",
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
