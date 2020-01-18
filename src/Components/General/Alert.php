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
     * Type setter.
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
     * Type getter.
     *
     * @return string
     * @throws \RuntimeException
     */
    public function getTextKey(): string
    {
        if (!isset($this->text_key)) {
            throw new \RuntimeException(sprintf('Alert text key not set in [%s]', get_class($this)));
        }

        return sprintf('text.%s', $this->text_key);
    }


}
