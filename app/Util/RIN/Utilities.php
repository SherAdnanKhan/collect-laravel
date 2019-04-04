<?php

namespace App\Util\RIN;

use Carbon\CarbonInterval;

/**
 * RIN helper utility methods.
 */
class Utilities
{
    /**
     * Parse a duration from ISO-8601:2004 to seconds
     *
     * @param  string $duration
     * @return int
     */
    public static function parseDuration(string $duration): int
    {
        // convert from https://en.wikipedia.org/wiki/ISO_8601 :2004 to int.
        return (int) CarbonInterval::make($duration)->total('seconds');
    }

    /**
     * Format a duration in seconds as ISO-8601:2004
     *
     * @param  int $duration
     * @return string
     */
    public static function formatDuration(int $duration): string
    {
        // format as https://en.wikipedia.org/wiki/ISO_8601 :2004 from int.
        return CarbonInterval::seconds($duration)->spec();
    }
}
