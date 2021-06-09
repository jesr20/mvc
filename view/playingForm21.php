<?php

/**
 * Standard view template to generate a simple web page, or part of a web page.
 */

declare(strict_types=1);

use jesr20\Dice\Dice;
use jesr20\Dice\DiceHand;

$header = $header ?? null;
$message1 = $message1 ?? null;
$message2 = $message2 ?? null;
$message3 = $message3 ?? null;
$action = $action ?? null;
$numberOfDices = $_SESSION["numberOfDices"] ?? null;
$sum = $sum ?? null;
$player = $_SESSION["player"] ?? null;
$cpu = $_SESSION["cpu"] ?? null;

?><h1><?= $header ?></h1>

<h1><?= $message1 ?></h1>
<p><?= $message3 . $sum ?></p>
<p><?= $message2 ?></p>

<form method="post" action="<?= $action ?>">
    <p>
        <input type="hidden" name="diceAmount" value="<?= $numberOfDices ?>">
        <button type="submit" name="move" value="Roll">Roll</button>
        <button type="submit" name="move" value="Stop">Stop</button>
    </p>
</form>

<h3>Score</h3>
<p>Player: <?= $player ?></p>
<p>Computer: <?= $cpu ?></p>
