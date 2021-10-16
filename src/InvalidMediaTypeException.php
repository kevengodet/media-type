<?php

namespace Keven\MediaType;

final class InvalidMediaTypeException extends \InvalidArgumentException
{
    public static function create(string $mediaType): self
    {
        return new self("Media type '$mediaType' is invalid.");
    }
}
