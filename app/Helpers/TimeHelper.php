<?php

namespace App\Helpers;

use Carbon\Carbon;

class TimeHelper
{
    /**
     * Convert time to Karachi Time (PKT) and format it.
     *
     * @param mixed $time
     * @param string|null $format
     * @return string
     */
    public static function karachiTime($time, $format = null)
    {
        return self::formatTime($time, 'Asia/Karachi', $format);
    }

    /**
     * Convert time to Central Time (Chicago) and format it.
     *
     * @param mixed $time
     * @param string|null $format
     * @return string
     */
    public static function chicagoTime($time, $format = null)
    {
        return self::formatTime($time, 'America/Chicago', $format);
    }

    /**
     * Format time in any timezone.
     *
     * @param mixed $time
     * @param string $timezone
     * @param string|null $format
     * @return string
     */
    public static function formatTime($time, $timezone = 'UTC', $format = null)
    {
        // Use the default format if $format is not provided
        $format = $format ?: 'd-m-Y h:i:s A';

        // Convert time to Carbon instance
        $carbonTime = Carbon::parse($time);

        // Convert to the specified timezone
        $carbonTime->setTimezone($timezone);

        // Format the time according to the specified format
        return $carbonTime->format($format);
    }
}
