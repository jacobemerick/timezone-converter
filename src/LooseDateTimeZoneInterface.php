<?php

namespace Jacobemerick\DateTimeZone;

interface LooseDateTimeZoneInterface extends DateTimeZoneInterface
{

    public function getAlternativeName($format);

}

