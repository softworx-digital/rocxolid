<?php

namespace Softworx\RocXolid\Http\Responses;

use Illuminate\Support\Collection;
use Illuminate\Support\MessageBag;
// rocXolid utils
use Softworx\RocXolid\Http\Responses\Contracts\AjaxResponse;

/**
 * Populates AJAX response into JSON formatted message.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class JsonAjaxResponse implements AjaxResponse
{
    /**
     * @var \Illuminate\Support\MessageBag $message_bag Container holding instructed response data.
     */
    protected $message_bag;

    /**
     * @var \Illuminate\Support\Collection $raw Container holding raw response data.
     */
    protected $raw;

    /**
     * Constructor.
     *
     * @param \Illuminate\Support\MessageBag $message_bag Container to hold the response data.
     */
    public function __construct(MessageBag $message_bag)
    {
        $this->message_bag = $message_bag;
        $this->raw = collect();
    }

    /**
     * {@inheritdoc}
     */
    public function raw(string $key, ?string $value): AjaxResponse
    {
        $this->raw->put($key, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function callFn(string $fn, ...$args): AjaxResponse
    {
        $this->message_bag->add('callFn', [
            'fn' => $fn,
            'args' => $args,
        ]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function errors(Collection $errors): AjaxResponse
    {
        $this->message_bag->add('errors', $errors);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function replace(string $selector, string $content, bool $selector_is_id = true): AjaxResponse
    {
        $selector = $selector_is_id ? sprintf('#%s', $selector) : $selector;

        $this->message_bag->add('replace', [
            $selector => $content
        ]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function insert(string $selector, string $content, bool $selector_is_id = true): AjaxResponse
    {
        $selector = $selector_is_id ? sprintf('#%s', $selector) : $selector;

        $this->message_bag->add('insert', [
            $selector => $content
        ]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function append(string $selector, string $content, bool $selector_is_id = true): AjaxResponse
    {
        $selector = $selector_is_id ? sprintf('#%s', $selector) : $selector;

        $this->message_bag->add('append', [
            $selector => $content
        ]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function val(string $selector, string $value, bool $selector_is_id = true): AjaxResponse
    {
        $selector = $selector_is_id ? sprintf('#%s', $selector) : $selector;

        $this->message_bag->add('val', [
            $selector => $value
        ]);

        return $this;
    }

    /**
    * {@inheritdoc}
    */
    public function destroy(string $selector, bool $selector_is_id = true): AjaxResponse
    {
        $selector = $selector_is_id ? sprintf('#%s', $selector) : $selector;

        $this->message_bag->add('destroy', $selector);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function empty(string $selector, bool $selector_is_id = true): AjaxResponse
    {
        $selector = $selector_is_id ? sprintf('#%s', $selector) : $selector;

        $this->message_bag->add('empty', $selector);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function modal(string $content): AjaxResponse
    {
        $this->message_bag->add('modal', $content);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function modalOpen(string $selector, bool $selector_is_id = true): AjaxResponse
    {
        $selector = $selector_is_id ? sprintf('#%s', $selector) : $selector;

        $this->message_bag->add('modalOpen', $selector);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function modalClose(string $selector, bool $selector_is_id = true): AjaxResponse
    {
        $selector = $selector_is_id ? sprintf('#%s', $selector) : $selector;

        $this->message_bag->add('modalClose', $selector);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function notifyNotice(string $text): AjaxResponse
    {
        return $this->notify($text, 'notice');
    }

    /**
     * {@inheritdoc}
     */
    public function notifyInfo(string $text): AjaxResponse
    {
        return $this->notify($text, 'info');
    }

    /**
     * {@inheritdoc}
     */
    public function notifySuccess(string $text): AjaxResponse
    {
        return $this->notify($text, 'success');
    }

    /**
     * {@inheritdoc}
     */
    public function notifyError(string $text): AjaxResponse
    {
        return $this->notify($text, 'error');
    }

    /**
     * {@inheritdoc}
     */
    public function notify(string $text, string $type = null): AjaxResponse
    {
        $this->message_bag->add('notify', [
            'text' => $text,
            'type' => $type,
        ]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function redirect(string $url): AjaxResponse
    {
        $this->message_bag->add('redirect', $url);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function openTab(string $url): AjaxResponse
    {
        $this->message_bag->add('openTab', $url);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function reload(): AjaxResponse
    {
        $this->message_bag->add('reload', true);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function file64(string $content, ?string $filename = null): AjaxResponse
    {
        $this->message_bag->add('file64', collect([
            'base64' => base64_encode($content),
            'type' => (new \finfo())->buffer($content, FILEINFO_MIME_TYPE),
            'filename' => $filename,
        ])->filter());

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function get(): array
    {
        return $this->message_bag->jsonSerialize() + $this->raw->jsonSerialize();
    }
}
