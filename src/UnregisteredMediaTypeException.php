<?php

namespace Keven\MediaType;

final class UnregisteredMediaTypeException extends \InvalidArgumentException
{
    public static function create(string $mediaType): self
    {
        return new self("Media type '$mediaType' is unregistered.");
    }
}
