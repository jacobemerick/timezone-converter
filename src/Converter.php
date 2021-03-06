<?php

namespace Jacobemerick\TimezoneConverter;

use DateTimeZone;
use Exception,
    DomainException,
    InvalidArgumentException,
    RuntimeException;

class Converter
{

    const IANA_FORMAT = 1;
    const UTC_FORMAT = 2;
    const MILITARY_FORMAT = 4;
    const ABBREVIATION_FORMAT = 8;
    const RAILS_FORMAT = 16;
    const ANY_FORMAT = 2047;

    protected $format;

    public function __construct($format = self::ANY_FORMAT)
    {
        if (!in_array($format, [
            self::IANA_FORMAT,
            self::UTC_FORMAT,
            self::MILITARY_FORMAT,
            self::ABBREVIATION_FORMAT,
            self::RAILS_FORMAT,
            self::ANY_FORMAT
        ])) {
            throw new DomainException('Invalid format parameters passed into constructor');
        }

        $this->format = $format;
    }

    public function __invoke($timezone)
    {
        if ($this->format != self::IANA_FORMAT) {
            $timezone = $this->convertTimezone($timezone);
        }
        return new DateTimeZone($timezone);
    }

    protected function convertTimezone($timezone)
    {
        switch ($this->format) {
            case self::IANA_FORMAT:
                return $timezone;
                break;
            case self::UTC_FORMAT:
                return $this->convertFromUTC($timezone);
                break;
            case self::MILITARY_FORMAT:
                return $this->convertFromMilitary($timezone);
                break;
            case self::ABBREVIATION_FORMAT:
                return $this->convertFromAbbreviation($timezone);
                break;
            case self::RAILS_FORMAT:
                return $this->convertFromRails($timezone);
                break;
            case self::ANY_FORMAT:
                try {
                    return $this->convertFromUTC($timezone);
                } catch (Exception $e) {}

                try {
                    return $this->convertFromAbbreviation($timezone);
                } catch (Exception $e) {}

                try {
                    return $this->convertFromRails($timezone);
                } catch (Exception $e) {}

                try {
                    return $this->convertFromMilitary($timezone);
                } catch (Exception $e) {}

                throw new InvalidArgumentException('Could not find a valid timezone format');
                break;
            default:
                throw new DomainException('Invalid format used for conversion');
                break;
        }
    }

    protected function convertFromUTC($timezone)
    {
        $timezone = str_replace(':', '', $timezone);
        if (ctype_digit($timezone[0])) {
            $timezone = "+{$timezone}";
        }
        if (strlen($timezone) < 5) {
            $timezone = substr_replace($timezone, '0', 1, 0);
        }

        $utc_timezones = $this->getUTCTimezones();
        if (!array_key_exists($timezone, $utc_timezones)) {
            throw new InvalidArgumentException('Could not find a relevant UTC offset to map');
        }
        return $utc_timezones[$timezone];
    }

    protected function convertFromMilitary($timezone)
    {
        $timezone = $timezone[0];
        $timezone = strtolower($timezone);

        $military_timezones = $this->getMilitaryTimezones();
        if (!array_key_exists($timezone, $military_timezones)) {
            throw new InvalidArgumentException('Could not find a relevant military timezone to map');
        }
        return $military_timezones[$timezone];
    }

    protected function convertFromAbbreviation($timezone)
    {
        $timezone = strtolower($timezone);

        $abbreviation_timezones = $this->getAbbreviationTimezones();
        if (!array_key_exists($timezone, $abbreviation_timezones)) {
            throw new InvalidArgumentException('Could not find a relevant timezone abbreviation');
        }
        return $abbreviation_timezones[$timezone];
    }

    protected function convertFromRails($timezone)
    {
        $rails_timezones = $this->getRailsTimezones();
        if (!array_key_exists($timezone, $rails_timezones)) {
            throw new InvalidArgumentException('Could not find a relevant Rails timezone to map');
        }
        return $rails_timezones[$timezone];
    }

    protected $utc_timezones;
    protected function getUTCTimezones()
    {
        if (!isset($this->utc_timezones)) {
            $this->utc_timezones = $this->loadTimezones('utc');
        }
        return $this->utc_timezones;
    }

    protected $military_timezones;
    protected function getMilitaryTimezones()
    {
        if (!isset($this->military_timezones)) {
            $this->military_timezones = $this->loadTimezones('military');
        }
        return $this->military_timezones;
    }

    protected $abbreviation_timezones;
    protected function getAbbreviationTimezones()
    {
        if (!isset($this->abbreviation_timezones)) {
            $this->abbreviation_timezones = $this->loadTimezones('abbreviations');
        }
        return $this->abbreviation_timezones;
    }

    protected $rails_timezones;
    protected function getRailsTimezones()
    {
        if (!isset($this->rails_timezones)) {
            $this->rails_timezones = $this->loadTimezones('rails');
        }
        return $this->rails_timezones;
    }

    protected function loadTimezones($type)
    {
        $path = __DIR__ . "/../data/timezone-{$type}.json";
        $handle = @fopen($path, 'r');
        if ($handle === false) {
            throw new RuntimeException("Could not open data file for {$type} timezones");
        }
        $contents = fread($handle, filesize($path));
        fclose($handle);

        $json_contents = json_decode($contents, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException("Could not decode json for {$type} timezones");
        }
        return $json_contents;
    }

}

