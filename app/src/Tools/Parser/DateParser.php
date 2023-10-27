<?php

declare(strict_types=1);

namespace App\Tools\Parser;

class DateParser
{
    final public const DATE_FORMAT = 'Y-m-d';
    final public const FR_DATE_FORMAT = 'd/m/Y';
    final public const FR_DATETIME_FORMAT = 'd/m/Y H:i:s';
    final public const DATETIME_FORMAT = 'Y-m-d H:i:s';

    public function getDateTime(string $date = 'now', string $timeZone = null): ?\DateTime
    {
        try {
            $dateTime = new \DateTime($date);

            if ($timeZone) {
                $dateTime->setTimezone(new \DateTimeZone($timeZone));
            }

            return $dateTime;
        } catch (\Exception) {
            return null;
        }
    }

    public function createFromMixed(mixed $date, string $format = self::FR_DATE_FORMAT): ?\DateTime
    {
        if ($date instanceof \DateTime) {
            return $date;
        }

        if (\is_numeric($date)) {
            return $this->createFromTimestamp($date);
        }

        if (\is_string($date)) {
            return \DateTime::createFromFormat($format, $date);
        }

        return null;
    }

    private function createFromTimestamp(int $timestamp): \DateTime
    {
        return new \DateTime(\sprintf('@%s', $timestamp));
    }
}
