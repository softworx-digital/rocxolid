<?php

namespace Softworx\RocXolid\Components\General;

use Illuminate\Support\Collection;
// rocXolid components
use Softworx\RocXolid\Components\AbstractActiveComponent;

/**
 * General alert component.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class Alert extends AbstractActiveComponent
{
    const TYPE_INFO = 'info';
    const TYPE_SUCCESS = 'success';
    const TYPE_WARNING = 'warning';
    const TYPE_ERROR = 'error';

    /**
     * @var string
     */
    protected $type;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $text = [];

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $text_key = [];

    /**
     * Type setter.
     *
     * @param string $type
     * @return \Softworx\RocXolid\Components\General\Alert
     */
    public function setType(string $type): Alert
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Type getter.
     *
     * @return string
     * @throws \RuntimeException
     */
    public function getType(): string
    {
        if (!isset($this->type)) {
            throw new \RuntimeException(sprintf('Alert type not set in [%s]', get_class($this)));
        }

        return $this->type;
    }

    /**
     * Text setter.
     *
     * @param string $type
     * @return \Softworx\RocXolid\Components\General\Alert
     */
    public function addText(string $text, string $wrapper = 'p'): Alert
    {
        $this->text = collect($this->text)->push([
            'text' => $text,
            'wrapper' => $wrapper
        ]);

        return $this;
    }

    /**
     * Text language key setter.
     *
     * @param string $type
     * @return \Softworx\RocXolid\Components\General\Alert
     */
    public function addTextKey(string $text_key, string $wrapper = 'p'): Alert
    {
        $this->text = collect($this->text)->push([
            'key' => sprintf('text.%s', $text_key),
            'wrapper' => $wrapper
        ]);

        return $this;
    }

    /**
     * Text assignment checker.
     *
     * @return bool
     */
    public function hasText(): bool
    {
        return !empty($this->text);
    }

    /**
     * Text getter.
     *
     * @return string
     */
    public function getText(): Collection
    {
        if (!$this->hasText()) {
            throw new \RuntimeException(sprintf('Alert text not set in [%s]', get_class($this)));
        }

        return $this->text;
    }
}
