<?php

namespace Softworx\RocXolid\Annotations;

abstract class AbstractAuthorizedAnnotation
{
    /**
     * @Required
     */
    protected $scopes;

    public function __construct(array $values)
    {
        if (isset($values['scopes'])) {
            $this->scopes = json_decode($this->fixJSON($values['scopes']));

            if (is_null($this->scopes)) {
                throw new \RuntimeException(sprintf('Invalid JSON format for scopes given: %s', $values['scopes']));
            } elseif (!is_array($this->scopes)) {
                throw new \RuntimeException(sprintf('Invalid type for scopes given: %s, JSON array expected', $values['scopes']));
            }
        }
    }

    public function getScopes()
    {
        return $this->scopes;
    }

    protected function fixJSON(string $json) {
        $regex = <<<'REGEX'
~
    "[^"\\]*(?:\\.|[^"\\]*)*"
    (*SKIP)(*F)
    | '([^'\\]*(?:\\.|[^'\\]*)*)'
~x
REGEX;

        return preg_replace_callback($regex, function ($matches) {
            return '"' . preg_replace('~\\\\.(*SKIP)(*F)|"~', '\\"', $matches[1]) . '"';
        }, $json);
    }
}
