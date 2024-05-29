<?php

namespace App\Helpers;

use Carbon\Carbon;

class TimeHelper
{
    private static  $format = 'd/M/y h:i A T';
    /**
     * Convert time to Karachi Time (PKT) and format it.
     *
     * @param mixed $time
     * @param string|null $format
     * @return string
     */
    public static function karachiTime($time)
    {

        return self::formatTime($time, 'Asia/Karachi');
    }

    /**
     * Convert time to Central Time (Chicago) and format it.
     *
     * @param mixed $time
     * @param string|null $format
     * @return string
     */
    public static function chicagoTime($time)
    {

        return self::formatTime($time, 'America/Chicago');
    }

    /**
     * Format time in any timezone.
     *
     * @param mixed $time
     * @param string $timezone
     * @param string|null $format
     * @return string
     */
    public static function formatTime($time, $timezone = 'UTC')
    {
        // Convert time to Carbon instance
        $carbonTime = Carbon::parse($time);

        // Convert to the specified timezone
        $carbonTime->setTimezone($timezone);

        // Format the time according to the specified format
        return $carbonTime->format(self::$format);
    }
}
