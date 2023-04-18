<?php
namespace Shay3gan\UNIG\Commands;

use SergiX44\Nutgram\Nutgram as Bot;

class StaticTexts {
    public function start(Bot $bot): void
    {
        $bot->sendMessage("hi", [
            'reply_to_message_id' => $bot->messageId(),
        ]);
    }

    public function help(Bot $bot): void
    {
        $help = "+ where is {teacher} right now?
- /locate {teacher}
➖➖
+ list of {teacher} classes filtered by {title} and {group}?
- /list {teacher} {title} {group}
➖➖
+ whois teaching in the class {location} right now?
- /whois {location}
➖➖
+ list of classes at {location} {day} {time}?
- /classes {location} {day} {time}
➖➖
+ same as /classes, but people count?
- /people {location} {day} {time}
➖➖
+ what do students think about {teacher}?
- /wiki {teacher}";

        $bot->sendMessage($help, [
            'reply_to_message_id' => $bot->messageId(),
        ]);
    }
}
