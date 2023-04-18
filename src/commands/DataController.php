<?php
namespace Shay3gan\UNIG\Commands;

use SergiX44\Nutgram\Nutgram as Bot;
use Shay3gan\UNIG\Config;

class DataController {
    private Bot $bot;

    public function locate(Bot $bot, string $teacher): void
    {
        $this->bot = $bot;

        $time = $this->getTime();
        $locate = Config::db()->query("SELECT `title`, `location`, `time`, `gp` FROM `classes` where `teacher` LIKE '%$teacher%' and $time[query]")->fetch_assoc();

        if (!$locate) {
            $bot->sendMessage("idk where is $teacher at day $time[day] time $time[time]", $this->options());
        }
        else {
            $this->export($locate, false);
        }
    }

    public function list(Bot $bot, string $teacher, $title = null, $group = null): void
    {
        $this->bot = $bot;

        $this->validate($title, $group);

        $list = Config::db()->query("SELECT `title`, `location`, `time`, `gp`, `teacher` FROM `classes` where `teacher` LIKE '%$teacher%' and `title` like '%$title%' and `gp` like '%$group%'")->fetch_all(MYSQLI_ASSOC);

        if (!$list) {
            $bot->sendMessage("no any result for $teacher teaching $title in $group", $this->options());
        }
        else {
            $this->export($list);
        }
    }

    public function whois(Bot $bot, string $location): void
    {
        $this->bot = $bot;

        $time = $this->getTime();
        $whois = Config::db()->query("SELECT `title`, `location`, `teacher`, `gp`, `time` FROM `classes` where `location` LIKE '%$location%' and $time[query]")->fetch_all(MYSQLI_ASSOC);

        if (!$whois) {
            $bot->sendMessage("idk whois in $location", $this->options());
        }
        else {
            $this->export($whois);
        }
    }

    public function whatOn(Bot $bot, string $location, string $day, string $time): void
    {
        $this->bot = $bot;

        $whatOn = Config::db()->query("SELECT `title`, `location`, `teacher`, `gp`, `time` from `classes` where `location` LIKE '%$location%' and `day` = '$day' and '$time' between `start` and `end`")->fetch_all(MYSQLI_ASSOC);

        if (!$whatOn) {
            $bot->sendMessage("didn't find anything on day $day, time $time at $location", $this->options());
        }
        else {
            $this->export($whatOn);
        }
    }

    public function people(Bot $bot, string $location, string $day, string $time): void
    {
        $this->bot = $bot;

        $whatOn = count(Config::db()->query("SELECT `title`, `location`, `teacher`, `gp`, `time` from `classes` where `location` LIKE '%$location%' and `day` = '$day' and '$time' between `start` and `end`")->fetch_all(MYSQLI_ASSOC));

        if (!$whatOn) {
            $bot->sendMessage("didn't find anything on day $day, time $time at $location", $this->options());
        }
        else {
            $bot->sendMessage("have $whatOn classes, people count (estimated 30 per class): " . $whatOn * 30, $this->options());
        }
    }

    public function wikiSearch(Bot $bot, string $teacher): void
    {
        $this->bot = $bot;

        $searchResult = Config::db()->query("SELECT `id`, `name`, `details` from `wiki` where `name` LIKE '%$teacher%'")->fetch_all(MYSQLI_ASSOC);

        if (!$searchResult) {
            $bot->sendMessage("we have no information about the mentioned teacher", $this->options());
        }
        else {
            $list = [];
            foreach ($searchResult as $item) {
                $list[] = "🧑‍🏫 $item[name]: /wiki_$item[id]";
            }

            $bot->sendMessage(implode("\n\n", $list), $this->options());
        }
    }

    public function wikiDetails(Bot $bot, int $id): void
    {
        $this->bot = $bot;

        $result = Config::db()->query("SELECT `details` from `wiki` where `id` = '$id'")->fetch_assoc();

        if (!$result) {
            $bot->sendMessage("invalid id", $this->options());
        }
        else {
            $bot->sendMessage($result['details'], $this->options());
        }
    }


    private function validate(&...$values): void
    {
        foreach ($values as &$val) {
            if ($val === "*") {
                $val = null;
            }
        }
    }

    private function export($data, $multidimensional = true): void
    {
        $response = $this->humanizedArray($data, $multidimensional);
        $length = strlen($response);
        $separator = 4096;

        $this->bot->sendMessage(
            $length >= $separator ? mb_substr($response, 0, $separator) : $response,
            $this->options()
        );
    }

    private function humanizedArray(array $data, $multidimensional): string
    {
        $titles = [
            'title' => '📗',
            'location' => '📍',
            'teacher' => '👨‍🏫',
            'gp' => '📚',
            'time' => '⌚',
        ];
        $result = "";

        if ($multidimensional) {
            foreach ($data as $item) {
                foreach ($item as $key => $value) {
                    $title = $titles[$key];
                    $result .= "$title $value\n";
                }
                $result .= "\n➖➖➖\n\n";
            }
        }
        else {
            foreach ($data as $key => $value) {
                $title = $titles[$key];
                $result .= "$title $value\n";
            }
        }

        return $result;
    }

    private function options(int $messageId = null): array
    {
        return [
            "reply_to_message_id" => $messageId ?? $this->bot->messageId(),
        ];
    }

    private function getTime(): array
    {
        $day = tr_num(jdate("w"));
        $time = date("Gi");

        return [
            'day' => $day,
            'time' => $time,
            'query' => "`day` = '$day' and '$time' between `start` and `end`",
        ];
    }
}
