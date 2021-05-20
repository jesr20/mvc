<?php

/**
 * Standard view template to generate a simple web page, or part of a web page.
 */

declare(strict_types=1);

$header = $header ?? null;
$message = $message ?? null;
$action = $action ?? null;
$output = $output ?? null;
$reset = $reset ?? null;
$player = $_SESSION["player"] ?? null;
$cpu = $_SESSION["cpu"] ?? null;

?><h1><?= $header ?></h1>

<p><?= $message ?></p>

<h3>Choose how many dices to play with:</h3>
<form method="post" action="<?= $action ?>">
    <p>
        <button type="submit" name="numberOfDices" value="1">1</button>
        <button type="submit" name="numberOfDices" value="2">2</button>
    </p>
</form>

<h3>Score</h3>
<p>Player: <?= $player ?></p>
<p>Computer: <?= $cpu ?></p>

<p><button><a href="<?= $reset ?>">Reset scores</a></button></p>
