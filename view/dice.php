<?php

/**
 * Standard view template to generate a simple web page, or part of a web page.
 */

declare(strict_types=1);

$header = $header ?? null;
$message = $message ?? null;

?><h1><?= $header ?></h1>

<p><?= $message ?></p>

<!-- <p>DICE!!!!</p>

<p><?= $dieLastRoll ?></p>

<p>Dicehand</p>

<p><?= $diceHandRoll ?></p>
<p><?= $diceHandRoll1 ?></p> -->
