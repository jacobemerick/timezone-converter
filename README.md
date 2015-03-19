# timezone-converter

A simply and ugly little invokable class to convert different timezone formats into IANA-approved DateTimeZones. While PHP has some awesome parsing to handle many different datetime formats, it is not so forgiving about timezones. This class helps out with this.

## Usage

This currently supports converting between four different timezone formats. You can either define which timezone you are interested in or just throw an 'any' match at it, though that is a bit more expensive.

```php
// basic instantiation
use Jacobemerick\TimezoneConverter\Converter;

$converter = new Converter(Converter::ABBREVIATION_FORMAT);
$timezone = $converter('est');
```

The `$timezone` variable is now a instance of DateTimeZone, with an internal setting of `America/New_York`.

### UTC Formats

UTC Formats are pretty tricky to convert between, as a UTC offset contains no contextual information about standard/daylight settings. Use this option with care.

```php
$converter = new Converter(Converter::UTC_FORMAT);
$timezone = $converter('-0500'); // America/New_York
```

### Military Formats

As military formats expect to be a hard return of an offset, without any context of daylight savings time or geographical oddities, this option returns timezones of Etc/GMT(offset). You can pass in values in either NATO phonetic alphabet or just the English letter.

```php
$converter = new Converter(Converter::MILITARY_FORMAT);
$timezone = $converter('Romeo'); // Etc/GMT-5
```

### Abbreviation Formats

Timezone abbreviations are inherently difficult to work with, as they are not as comprehensive as the IANA format and they are duplicated for several zones. The responses can be assumed to be best guess conversions.

```php
$converter = new Converter(Converter::ABBREVIATION_FORMAT);
$timezone = $converter('est'); // America/New_York
```

### Rails Formats

There is a Rails gem that attempts to 'simplify' the default list of IANA formats into a list of 146 options. Which is great until you need to convert back.

```php
$converter = new Converter(Converter::RAILS_FORMAT);
$timezone = $converter('Eastern Time (US & Canada)'); // America/New_York
```

### Other Options

For completionist sake, you can also pass in IANA timezone formats. Also, you can pass in 'any' queries, which will loop through options and try to come up with something. Consider this a last-ditch effort.

```php
$converter = new Converter(Converter::IANA_FORMAT);
$timezone = $converter('America/New_York'); // America/New_York

$converter = new Converter(Converter::ANY_FORMAT);
$timezone = $converter('est'); // America/New_York
```

## Installation

Through [composer](http://getcomposer.org):

```bash
$ composer require jacobemerick/timezone-converter:~0.1
```

