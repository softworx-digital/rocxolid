<?php

namespace Softworx\RocXolid\Traits;

use Illuminate\Support\Facades\Log;

/**
 * Enables object to log messages.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait Loggable
{
    /**
     * Log message or whatever needed.
     *
     * @param mixed $message Message or object to be logged.
     * @param bool $log_method Flag to log caller method.
     * @return self
     */
    protected function log($message, $log_method = false)
    {
        if (!$this->doLogging()) {
            return $this;
        }

        if ($log_method) {
            $backtrace = collect(debug_backtrace());
            $caller = $backtrace->get(1);

            Log::channel('single')->debug(sprintf('%s::%s()', $caller['class'], $caller['function']));
        }

        Log::channel('single')->debug($message);

        return $this;
    }

    /**
     * Check whether to do the logging.
     *
     * @return bool
     */
    private function doLogging(): bool
    {
        return !isset($this->log) || $this->log;
    }
}
