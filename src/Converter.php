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

