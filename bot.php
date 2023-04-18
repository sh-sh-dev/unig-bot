<?php
require_once "vendor/autoload.php";

use SergiX44\Nutgram\Nutgram as Bot;
use Shay3gan\UNIG\Commands\DataController;
use Shay3gan\UNIG\Commands\StaticTexts;

$config = new \Shay3gan\UNIG\Config();

$bot = new Bot($config::getToken());
$bot->setRunningMode(\SergiX44\Nutgram\RunningMode\Webhook::class);

// Commands

$bot->onCommand("start", [StaticTexts::class, "start"])
    ->description("just says hi");

$bot->onCommand("help", [StaticTexts::class, "help"])
    ->description("how to heck do you use this bot");

$bot->onCommand("locate {teacher}", [DataController::class, "locate"])
    ->description("where is {teacher} right now?");

$bot->onCommand("list {teacher} / {title} / {group}", [DataController::class, "list"])
    ->description("list of {teacher} classes filtered by {title}, {group} and {day}");

$bot->onCommand("whois {location}", [DataController::class, "whois"])
    ->description("whois teaching in the class {location} right now");

$bot->onCommand("classes {location} / {day} / {time}", [DataController::class, "whatOn"])
    ->description("list of classes at {location} {day} {time}");

$bot->onCommand("people {location} / {day} / {time}", [DataController::class, "people"])
    ->description("same as /classes, but people count");

$bot->onCommand("wiki {teacher}", [DataController::class, "wikiSearch"])
    ->description("search in wiki about an {teacher}");

$bot->onCommand("wiki_{id}", [DataController::class, "wikiDetails"]);

// Run

$bot->registerMyCommands();

$bot->run();
