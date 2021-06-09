<?php

/**
 * Standard view template to generate a simple web page, or part of a web page.
 */

declare(strict_types=1);

$header = $header ?? null;
$message = $message ?? null;
$output = $output ?? null;
$totals = $totals ?? null;
$player = $player ?? null;
$cpu = $cpu ?? null;
$action = $action ?? null;

?><h1><?= $header ?></h1>

<p><?= $message ?></p>
<?php if ($totals != null) : ?>
    <p><?= $totals ?></p>
<?php endif; ?>
<p><?= $output ?></p>
<p><?= "Score:" ?><br><?= "Player: " . $player ?><br><?= "Computer: " . $cpu ?></p>

<form method="post" action="<?= $action ?>">
    <p>
        <button type="submit" name="" value="">Play again</button>
    </p>
</form>
