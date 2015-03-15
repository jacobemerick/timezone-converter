<?php

namespace Jacobemerick\DateTimeZone;

use DateTime;

interface DateTimeZoneInterface
{

    public function getLocation();

    public function getName();

    public function getOffset($datetime);

    public function getTransitions($timestamp_begin = null, $timestamp_end = null);

    public static function listAbbreviations();

    public static function listIdentifiers($what = DateTimeZone::ALL, $country = null);

}

