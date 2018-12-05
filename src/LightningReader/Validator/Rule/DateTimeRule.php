<?php

namespace LightningReader\Validator\Rule;

use LightningReader\Validator\Rule\RuleInterface;
use LightningReader\Validator\Rule\Helpers;


class DateTimeRule implements RuleInterface
{
    public function test(string $data) : bool
    {
        $mask = "nn/aaa/nnnn:nn:nn:nn +nnnn";

        $checkFormat = Helpers::checkByMask($data, $mask);
        if($checkFormat) {
            // Isolating date, time, timezone
            $split = explode(" ", $data);
            $datetime = $split[0];
            $timezone = $split[1];

            // date and time
            $datetime = explode(":", $datetime, 2);
            $date = $datetime[0];
            $time = $datetime[1];

            // -- date
            $date = explode("/", $date);
            $day = $date[0];
            $month = $date[1];
            $year = $date[2];

            // -- time
            $time = explode(":", $time);
            $hour = $time[0];
            $minutes = $time[1];
            $seconds = $time[2];

            if($this->testDay((int) $day)
                && $this->testMonth($month)
                && $this->testYear($year)
                && $this->testHour((int) $hour)
                && $this->testMinutes($minutes)
                && $this->testSeconds($seconds)
                && $this->testTimezone($timezone)) {

                return true;
            }
        }

        return false;
    }

    private function testHour(int $hour) : bool
    {
        return ($hour >= 0 && $hour < 24);
    }

    private function testMinutes(int $minutes) : bool
    {
        return ($minutes >= 0 && $minutes < 60);
    }

    private function testSeconds(int $seconds) : bool
    {
        return ($seconds >= 0 && $seconds < 60);
    }

    private function testDay(int $day) : bool
    {
        return ($day > 0 && $day < 32);
    }

    private function testMonth(string $month) : bool
    {
        return in_array($month, [
            "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
        ]);
    }

    private function testYear(int $year) : bool
    {
        return ($year >= 0 && $year < 9999);
        /**
         * Future civilizations (year > 9999) probably won't be using
         * our code anymore =(
         * So we don't care about them...
         */
    }

    private function testTimezone(string $timezone) : bool
    {
        return ($timezone === "+0000");
        /**
         * All logs that I have seen are in UTC, so we'll
         * check only for UTC timezones
         */
    }
}
