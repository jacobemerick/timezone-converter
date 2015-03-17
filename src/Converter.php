<?php

namespace Jacobemerick\TimezoneConverter;

use DateTimeZone;
use Exception,
    DomainException,
    UnexpectedValueException;

class Converter
{

    const IANA_FORMAT = 1;
    const UTC_FORMAT = 2;
    const ABBREVIATION_FORMAT = 4;
    const RUBY_FORMAT = 8;
    const ANY_FORMAT = 2047;

    protected $format;

    public function __construct($format = self::ANY_FORMAT)
    {
        if (!in_array($format, [
            self::IANA_FORMAT,
            self::UTC_FORMAT,
            self::ABBREVIATION_FORMAT,
            self::RUBY_FORMAT,
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
            case self::RUBY_FORMAT:
                return $this->convertFromRuby($timezone);
                break;
            case self::ANY_FORMAT:
                try {
                    return $this->convertFromUTC($timezone);
                } catch (Exception $e) {}

                try {
                    return $this->convertFromAbbreviation($timezone);
                } catch (Exception $e) {}

                try {
                    return $this->convertFromRuby($timezone);
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
        return 'America/Phoenix';
    }

    protected function convertFromAbbreviation($timezone)
    {
        return 'America/Phoenix';
    }

    protected function convertFromRuby($timezone)
    {
        return 'America/Phoenix';
    }

}

