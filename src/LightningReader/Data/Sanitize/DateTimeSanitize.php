<?php

namespace LightningReader\Data\Sanitize;

use LightningReader\Data\Sanitize\SanitizeInterface;


class DateTimeSanitize implements SanitizeInterface
{
    public function transform(string $data)
    {
        $split = explode(" ", $data);
        $datetime = $split[0];

        // date and time
        $datetime = explode(":", $datetime, 2);
        $date = $datetime[0];
        $time = $datetime[1];

        // -- date
        $date = explode("/", $date);
        $day = $date[0];
        $month = $this->monthStringToNumber($date[1]);
        $year = $date[2];

        // -- time
        $time = explode(":", $time);
        $hour = $time[0];
        $minutes = $time[1];
        $seconds = $time[2];

        return sprintf("%s-%s-%s %s:%s:%s",
            $year, $month, $day, $hour, $minutes, $seconds);
    }

    private function monthStringToNumber($month) : string
    {
        $list = [
            'Jan' => '01',
            'Feb' => '02',
            'Mar' => '03',
            'Apr' => '04',
            'May' => '05',
            'Jun' => '06',
            'Jul' => '07',
            'Aug' => '08',
            'Sep' => '09',
            'Oct' => '10',
            'Nov' => '11',
            'Dec' => '12',
        ];

        return $list[$month];
    }
}
