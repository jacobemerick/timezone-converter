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
        return 'America/Phoenix';
    }

}

