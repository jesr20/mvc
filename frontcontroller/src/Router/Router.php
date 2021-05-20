<?php

declare(strict_types=1);

namespace Mos\Router;

use jesr20\Dice\{
    Dice,
    DiceHand,
    GraphicalDice,
    Game
};

use function Mos\Functions\{
    destroySession,
    redirectTo,
    renderView,
    renderTwigView,
    sendResponse,
    url
};

$_SESSION["Dice"] = new Dice();
$_SESSION["DiceHand"] = new DiceHand();
$_SESSION["GraphicalDice"] = new GraphicalDice();
$_SESSION["Game"] = new Game();


/**
 * Class Router.
 */
class Router
{
    public int $numberOfDices;

    public static function dispatch(string $method, string $path): void
    {
        if ($method === "GET" && $path === "/") {
            $data = [
                "header" => "Index page",
                "message" => "Hello, this is the index page, rendered as a layout.",
            ];
            $body = renderView("layout/page.php", $data);
            sendResponse($body);
            return;
        } else if ($method === "GET" && $path === "/session") {
            $body = renderView("layout/session.php");
            sendResponse($body);
            return;
        } else if ($method === "GET" && $path === "/session/21") {
            destroySession();
            redirectTo(url("/game"));
            return;
        } else if ($method === "GET" && $path === "/session/destroy") {
            destroySession();
            redirectTo(url("/session"));
            return;
        } else if ($method === "GET" && $path === "/debug") {
            $body = renderView("layout/debug.php");
            sendResponse($body);
            return;
        } else if ($method === "GET" && $path === "/twig") {
            $data = [
                "header" => "Twig page",
                "message" => "Hey, edit this to do it youreself!",
            ];
            $body = renderTwigView("index.html", $data);
            sendResponse($body);
            return;
        } else if ($method === "GET" && $path === "/some/where") {
            $data = [
                "header" => "Rainbow page",
                "message" => "Hey, edit this to do it youreself!",
            ];
            $body = renderView("layout/page.php", $data);
            sendResponse($body);
            return;
        } else if ($method === "GET" && $path === "/dice") {
            $data = [
                "header" => "Dice",
                "message" => "Hey, edit this to do it yourself!",
            ];
            $body = renderView("layout/dice.php", $data);
            sendResponse($body);
            return;
        } else if ($method === "GET" && $path === "/game") {
            $_SESSION["player"] = $_SESSION["player"] ?? 0;
            $_SESSION["cpu"] = $_SESSION["cpu"] ?? 0;
            $_SESSION["Game"]->requestNumberOfDices();
            return;
        } else if ($method === "POST" && $path === "/game") {
            $_SESSION["Game"]->requestNumberOfDices();
            return;
        } else if ($method === "POST" && $path === "/game/start21process") {
            $_SESSION["numberOfDices"] = $_POST["numberOfDices"];
            redirectTo(url("/game/play21"));
            return;
        } else if ($method === "GET" && $path === "/game/play21") {
            $_SESSION["sum"] = 0;
            $_SESSION["Game"]->startGame($_SESSION["numberOfDices"]);
            return;
        } else if ($method === "POST" && $path === "/game/play21") {
            $_SESSION["move"] = $_POST["move"];
            $_SESSION["sum"] = $_SESSION["Game"]->rollGame(
                $_SESSION["move"],
                $_SESSION["numberOfDices"],
                $_SESSION["sum"],
                $_SESSION["player"],
                $_SESSION["cpu"]
            );
            $_SESSION["player"] = $_SESSION["Game"]->player;
            $_SESSION["cpu"] = $_SESSION["Game"]->cpu;
            return;
        } else if ($method === "POST" && $path === "/form/process21") {
            if ($_POST["numberOfDices"] < 2) {
                $string = "Playing 21 with one dice.";
            } else {
                $string = "Playing 21 with two dices.";
            }

            $data = [
                "header" => "Game21",
                "message1" => $string,
                "message2" => "Sum of rolled dices: ",
                "action" => "",
            ];

            $body = renderView("layout/21/play21.php", $data);
            sendResponse($body);
            return;
        } else if ($method === "GET" && $path === "/form/view") {
            $data = [
                "header" => "Form",
                "message" => "Press submit to send the message to the result page.",
                "action" => url("/form/process"),
                "output" => $_SESSION["output"] ?? null,
            ];
            $body = renderView("layout/form.php", $data);
            sendResponse($body);
            return;
        } else if ($method === "POST" && $path === "/form/process") {
            $_SESSION["output"] = $_POST["content"] ?? null;
            redirectTo(url("/form/view"));
            return;
        }

        $data = [
            "header" => "404",
            "message" => "The page you are requesting is not here. You may also checkout the HTTP response code, it should be 404.",
        ];
        $body = renderView("layout/page.php", $data);
        sendResponse($body, 404);
    }
}
