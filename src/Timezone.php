<?php

namespace Jacobemerick\TimezoneConverter;

class Timezone
{

    const RUBY_FORMAT = 'ruby';
    const UTC_FORMAT = 'utc';
    const IANA_FORMAT = 'iana';
    const APPREVIATION_FORMAT = 'abbr';

    public function __construct(
        $timezone_string,
        $format = self::IANA_FORMAT
    ) {
        $this->timezone_string = $timezone_string;
        $this->format = $format;
    }

    public function convert($format) {}

}

