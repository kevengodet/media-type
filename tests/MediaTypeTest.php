<?php

namespace Keven\MediaType\Tests;

use Keven\MediaType\MediaType;
use PHPUnit\Framework\TestCase;

final class MediaTypeTest extends TestCase
{
    /** @dataProvider data */
    public function test($mediaType, $type, $subtype, $tree = null, $suffix = null, $parameters = [])
    {
        $mediaTypeObject = MediaType::create($mediaType);

        $this->assertEquals($type, $mediaTypeObject->getType());
        $this->assertEquals($subtype, $mediaTypeObject->getSubtype());
        $this->assertEquals($tree, $mediaTypeObject->getTree());
        $this->assertEquals($suffix, $mediaTypeObject->getSuffix());
        $this->assertEquals($parameters, $mediaTypeObject->getParameters());
        $this->assertEquals($mediaType, (string) $mediaTypeObject);
    }

    /** @dataProvider data */
    public function testGetters($mediaType, $type, $subtype, $tree = null, $suffix = null, $parameters = [])
    {
        $mediaTypeObject = new MediaType($type, $subtype, $tree, $suffix, $parameters);

        $this->assertEquals($type, $mediaTypeObject->getType());
        $this->assertEquals($subtype, $mediaTypeObject->getSubtype());
        $this->assertEquals($tree, $mediaTypeObject->getTree());
        $this->assertEquals($suffix, $mediaTypeObject->getSuffix());
        $this->assertEquals($parameters, $mediaTypeObject->getParameters());
        $this->assertEquals($mediaType, (string) $mediaTypeObject);
    }

    /** @dataProvider data */
    public function testBuilder($mediaType, $type, $subtype, $tree = null, $suffix = null, $parameters = [])
    {
        $mediaTypeObject = (MediaType::create('a/a'))
            ->withType($type)
            ->withSubtype($subtype)
        ;

        if ($tree) {
            $mediaTypeObject = $mediaTypeObject->withTree($tree);
        }

        if ($suffix) {
            $mediaTypeObject = $mediaTypeObject->withSuffix($suffix);
        }

        if ($parameters) {
            $mediaTypeObject = $mediaTypeObject->withParameters($parameters);
        }

        $this->assertEquals($type, $mediaTypeObject->getType());
        $this->assertEquals($subtype, $mediaTypeObject->getSubtype());
        $this->assertEquals($tree, $mediaTypeObject->getTree());
        $this->assertEquals($suffix, $mediaTypeObject->getSuffix());
        $this->assertEquals($parameters, $mediaTypeObject->getParameters());
        $this->assertEquals($mediaType, (string) $mediaTypeObject);
    }

    public function data()
    {
        return [
            // media type,                type,             subtype,        tree,       suffix,     parameters
            ['application/x-executable',  'application',    'x-executable'],
            ['application/vnd.api+json',  'application',    'api',          'vnd',      'json'],
            ['image/svg+xml',             'image',          'svg',          null,        'xml'],
            ['text/plain; charset="UTF-8"', 'text',           'plain',        null,       null,       ['charset' => 'UTF-8']],
            ['video/mp4; codecs="avc1.640028"; another-param="another-value"', 'video', 'mp4', null, null, ['codecs' => 'avc1.640028', 'another-param' => 'another-value']],
        ];
    }
}
