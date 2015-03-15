<?php

namespace Jacobemerick\DateTimeZone;

use DateTime,
    DateTimeZone;

class LooseDateTimeZone extends DateTimeZone implements LooseDateTimeZoneInterface
{

    const IANA_FORMAT = 1;
    const UTC_FORMAT = 2;
    const ABBREVIATION_FORMAT = 4;
    const RUBY_FORMAT = 8;
    const ANY_FORMAT = 2047;

    protected $date_time_zone;

    public function __construct($timezone, $format = self::ANY_FORMAT)
    {
        $iana_timezone = $this->convertDateTimeZone($timezone, $format);
        try {
            $date_time_zone = new DateTimeZone($iana_timezone);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        $this->date_time_zone = $date_time_zone;
    }

    public function getLocation()
    {
        return $this->date_time_zone->getLocation();
    }

    public function getName()
    {
        return $this->date_time_zone->getName();
    }

    public function getAlternativeName($format)
    {
    }

    public function getOffset($datetime)
    {
        return $this->date_time_zone->getOffset($datetime);
    }

    public function getTransitions($timestamp_begin = null, $timestamp_end = null)
    {
        return $this->date_time_zone->getTransitions($timestamp_begin, $timestamp_end);
    }

    public static function listAbbreviations()
    {
        return call_user_func($this->date_time_zone, 'listAbbreviations');
    }

    public static function listIdentifiers($what = DateTimeZone::ALL, $country = null)
    {
        return call_user_func($this->date_time_zone, 'listIdentifiers', $what, $country);
    }

    protected function convertDateTimeZone($timezone, $format)
    {
        return 'America/Phoenix';
    }

}

