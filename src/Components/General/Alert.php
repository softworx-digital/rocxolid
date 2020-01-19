<?php

namespace Softworx\RocXolid\Components\General;

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
     * @var string
     */
    protected $text;

    /**
     * @var string
     */
    protected $text_key;

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
    public function setText(string $text): Alert
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Text assignment checker.
     *
     * @return bool
     */
    public function hasText(): bool
    {
        return isset($this->text);
    }

    /**
     * Text getter.
     *
     * @return string
     */
    public function getText(): string
    {
        if (!$this->hasText()) {
            throw new \RuntimeException(sprintf('Alert text not set in [%s]', get_class($this)));
        }

        return $this->text;
    }

    /**
     * Text language key setter.
     *
     * @param string $type
     * @return \Softworx\RocXolid\Components\General\Alert
     */
    public function setTextKey(string $text_key): Alert
    {
        $this->text_key = $text_key;

        return $this;
    }

    /**
     * Text language key assignment checker.
     *
     * @return bool
     */
    public function hasTextKey(): bool
    {
        return isset($this->text_key);
    }

    /**
     * Text language key getter.
     *
     * @return string
     * @throws \RuntimeException
     */
    public function getTextKey(): string
    {
        if (!$this->hasTextKey()) {
            throw new \RuntimeException(sprintf('Alert text language key not set in [%s]', get_class($this)));
        }

        return sprintf('text.%s', $this->text_key);
    }


}
