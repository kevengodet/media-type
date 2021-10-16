# keven/media-type

[![Latest Stable Version](https://poser.pugx.org/keven/media-type/v/stable.svg)](https://packagist.org/packages/keven/media-type)
[![Latest Unstable Version](https://poser.pugx.org/keven/media-type/v/unstable.svg)](https://packagist.org/packages/keven/media-type)
[![Total Downloads](https://poser.pugx.org/keven/media-type/downloads.svg)](https://packagist.org/packages/keven/media-type)
[![CI](https://github.com/minkphp/Mink/actions/workflows/tests.yml/badge.svg)](https://github.com/minkphp/Mink/actions/workflows/tests.yml)
[![License](https://poser.pugx.org/keven/media-type/license.svg)](https://packagist.org/packages/keven/media-type)

Simple RFC 6838 media type manipulation and validation library.

This module will parse a given media type into its component parts, like type,
subtype, and suffix. A formatter is also provided to put them back together and
the two can be combined to normalize media types into a canonical form.

## Installation

```sh
$ composer install keven/media-type
```

## API

```php
use Keven\MediaType\MediaType;
```

### Parsing

```php
$mediaType = MediaType::create('application/api+json');
$mediaType->getType(); // "application"
$mediaType->getTree(); // "vnd"
$mediaType->getSubtype(); // "api"
$mediaType->getsuffix(); // "json"
$mediaType->getParameters(); // ["charset" => "utf-8"]
$mediaType->hasParameter('charset'); // true
$mediaType->getParameter('charset'); // "utf-8"
```

Parse a media type string. This will return an object with the following
properties (examples are shown for the string `'image/vnd.svg+xml; charset=utf-8'`):

- `type`: The type of the media type (always lower case). Example: `'image'`
- `subtype`: The subtype of the media type (always lower case). Example: `'svg'`
- `tree`: The tree (~vendor) of the media type (always lower case). Example: `'vnd'`
- `suffix`: The suffix of the media type (always lower case). Example: `'xml'`
- `parameters`: The parameters added to the end of the media type. Exemple: `['charset' => 'utf-8']`

- If the given type string is invalid, then a `InvalidMediaTypeException` is thrown.

### Formatting

```php
$mediaType = new MediaType('image', 'svg', 'vnd', 'xml');
echo (string) $mediaType; // "image/vnd.svg+xml"
```

Format an object into a media type string. This will return a string of the
mime type for the given object.

### Validation

```php
MediaType::isValid('app/vnd.api+json; charset=utf-8'); // true (it's well formatted...)
MediaType::isValid('app/vnd.api+json; charset=utf-8', true); // false (...but the type is not a valid IANA type)
MediaType::create('unvalid media type'); // throws InvalidMediaTypeException
```

Validate a media type string. This will return `true` if the string is a well-
formatted media type, or `false` otherwise.

You can also assert if the type is valid by passing a second parameter.

## License

[MIT](LICENSE)
