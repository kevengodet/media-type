<?php

declare(strict_types=1);

namespace Keven\MediaType;

/**
 * @see https://datatracker.ietf.org/doc/html/rfc2045
 * @see https://www.iana.org/assignments/media-types/media-types.xhtml
 */
final class MediaType
{
    private const
        MATCH_OWS = "[ \t]*",
        MATCH_TOKEN = "[0-9A-Za-z!#$%&'*+.^_`|~-]+",
        MATCH_SUBTYPE = "[0-9A-Za-z!#$%&'*.^_`|~-]+",
        MATCH_TREE = "[0-9A-Za-z!#$%&'*+^_`|~-]+",
        MATCH_QUOTED_STRING = "\"(?P<value1>(?:[^\"\\\\]|\\.)*)\"",
        MATCH_PARAMETER = ';'.self::MATCH_OWS.'((?P<name>'.self::MATCH_TOKEN.')=((?P<value2>'.self::MATCH_TOKEN.')|'.self::MATCH_QUOTED_STRING.'))',
        MATCH_PATTERN = '/^(?P<type>'.self::MATCH_TOKEN.')\/((?P<tree>'.self::MATCH_TREE.')\.)?(?P<subtype>'.self::MATCH_SUBTYPE.')(\+(?P<suffix>'.self::MATCH_TOKEN.'))?(?P<parameters>('.self::MATCH_PARAMETER.')*)$/ui',
        REGISTERED_TYPES = ['application', 'audio', 'image', 'message', 'multipart', 'text', 'video', 'font', 'example', 'model', 'chemical']
    ;

    private string $type, $subtype;
    private ?string $tree, $suffix;
    private array $parameters;

    public function __construct(string $type, string $subtype, string $tree = null, string $suffix = null, array $parameters = [])
    {
        $this->type = $type;
        $this->tree = $tree;
        $this->subtype = $subtype;
        $this->suffix = $suffix;
        $this->parameters = $parameters;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getTree(): ?string
    {
        return $this->tree;
    }

    public function getSubtype(): string
    {
        return $this->subtype;
    }

    public function getSuffix(): ?string
    {
        return $this->suffix;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function hasParameter(string $name): bool
    {
        return isset($this->parameters[$name]);
    }

    public function getParameter(string $name, string $default = null): string
    {
        return $this->parameters[$name] ?? $default;
    }

    public function withType(string $type): self
    {
        return new self($type, $this->subtype, $this->tree, $this->suffix, $this->parameters);
    }

    public function withSubtype(string $subtype): self
    {
        return new self($this->type, $subtype, $this->tree, $this->suffix, $this->parameters);
    }

    public function withTree(string $tree): self
    {
        return new self($this->type, $this->subtype, $tree, $this->suffix, $this->parameters);
    }

    public function withSuffix(string $suffix): self
    {
        return new self($this->type, $this->subtype, $this->tree, $suffix, $this->parameters);
    }

    public function withParameters(array $parameters): self
    {
        return new self($this->type, $this->subtype, $this->tree, $this->suffix, $parameters);
    }

    public function withParameter(string $name, string $value): self
    {
        return new self($this->type, $this->subtype, $this->tree, $this->suffix, array_merge($this->parameters, [$name => $value]));
    }

    /** @throws InvalidMediaTypeException */
    public static function create(string $mediaType): self
    {
        if (!preg_match(self::MATCH_PATTERN, $mediaType, $matches)) {
            throw InvalidMediaTypeException::create($mediaType);
        }

        if ($matches['parameters']) {
            preg_match_all('/'.self::MATCH_PARAMETER.'/', $matches['parameters'], $parameters, PREG_SET_ORDER);
            $matches['parameters'] = [];
            foreach ($parameters as $parameter) {
                $matches['parameters'][$parameter['name']] = $parameter['value1'] ?? $parameter['value2'];
            }
        }

        return new self($matches['type'], $matches['subtype'], $matches['tree'] ?? null, $matches['suffix'] ?? null, is_array($matches['parameters']) ? $matches['parameters'] : []);
    }

    public static function isValid(string $mediaType, bool $isAlsoRegistered = false): bool
    {
        if (!preg_match(self::MATCH_PATTERN, $mediaType, $matches)) {
            return false;
        }

        if (!$isAlsoRegistered) {
            return true;
        }

        if (!in_array($matches['type'], self::REGISTERED_TYPES, true)) {
            return false;
        }

        $mediaTypeObject = new self($matches['type'], $matches['subtype'], $matches['tree'], $matches['suffix'], $matches['parameter']);

        return $mediaTypeObject->isRegistered();
    }

    public function isRegistered(): bool
    {
        return in_array($this->type, self::REGISTERED_TYPES);
    }

    public function __toString(): string
    {
        $mediaType = $this->type.'/';

        if ($this->tree) {
            $mediaType .= $this->tree.'.';
        }

        $mediaType .= $this->subtype;

        if ($this->suffix) {
            $mediaType .= '+'.$this->suffix;
        }

        foreach ($this->parameters as $name => $value) {
            $mediaType .= '; '.$name.'="'.$value.'"';
        }

        return $mediaType;
    }
}
