<?php

namespace Jacobemerick\TimezoneConverter;

use DateTimeZone;
use Exception,
    DomainException,
    RuntimeException,
    UnexpectedValueException;

class Converter
{

    const IANA_FORMAT = 1;
    const UTC_FORMAT = 2;
    const ABBREVIATION_FORMAT = 4;
    const RAILS_FORMAT = 8;
    const ANY_FORMAT = 2047;

    protected $format;

    public function __construct($format = self::ANY_FORMAT)
    {
        if (!in_array($format, [
            self::IANA_FORMAT,
            self::UTC_FORMAT,
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

                throw new UnexpectedValueException('Could not find a valid timezone format');
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
            throw new UnexpectedValueException('Could not find a relevant UTC offset to map');
        }
        return $utc_timezones[$timezone];
    }

    protected function convertFromAbbreviation($timezone)
    {
        return 'America/Phoenix';
    }

    protected function convertFromRails($timezone)
    {
        $rails_timezones = $this->getRailsTimezones();
        if (!array_key_exists($timezone, $rails_timezones)) {
            throw new UnexpectedValueException('Could not find a relevant Rails timezone to map');
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

