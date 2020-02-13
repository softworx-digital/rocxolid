<?php

namespace Softworx\RocXolid\Components\General;

use Illuminate\Support\Collection;
// rocXolid components
use Softworx\RocXolid\Components\General\Button;
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
     * @var \Illuminate\Support\Collection
     */
    protected $buttons = [];

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
     * @param string $wrapper
     * @return \Softworx\RocXolid\Components\General\Alert
     */
    public function addText(string $text, string $wrapper = 'p'): Alert
    {
        $this->text = collect($this->text)->push([
            'text' => $text,
            'wrapper' => $wrapper,
        ]);

        return $this;
    }

    /**
     * Text language key setter.
     *
     * @param string $type
     * @param string $wrapper
     * @return \Softworx\RocXolid\Components\General\Alert
     */
    public function addTextKey(string $text_key, string $wrapper = 'p'): Alert
    {
        $this->text = collect($this->text)->push([
            'key' => sprintf('text.%s', $text_key),
            'wrapper' => $wrapper,
        ]);

        return $this;
    }

    /**
     * Collection content setter.
     *
     * @param \Illuminate\Support\Collection $collection
     * @param string $wrapper
     * @param string $item_wrapper
     * @return \Softworx\RocXolid\Components\General\Alert
     */
    public function addCollection(Collection $collection, string $wrapper = 'ul', string $item_wrapper = 'li'): Alert
    {
        $this->text = collect($this->text)->push([
            'collection' => $collection,
            'wrapper' => $wrapper,
            'item_wrapper' => $wrapper,
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
     * @return \Illuminate\Support\Collection
     */
    public function getText(): Collection
    {
        if (!$this->hasText()) {
            throw new \RuntimeException(sprintf('Alert text not set in [%s]', get_class($this)));
        }

        return $this->text;
    }

    /**
     * Button setter.
     *
     * @param string $type
     * @return \Softworx\RocXolid\Components\General\Alert
     */
    public function addButton(Button $button): Alert
    {
        $this->buttons = collect($this->buttons)->push($button);

        return $this;
    }

    /**
     * Buttons getter.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getButtons(): Collection
    {
        return collect($this->buttons);
    }
}
