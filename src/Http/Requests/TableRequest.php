<?php

namespace Softworx\RocXolid\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as IlluminateFormRequest;

// @todo: cleanup
class TableRequest extends IlluminateFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow creates if the user is logged in
        //return \Auth::check();
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'name' => 'required|min:3|max:255'
        ];
    }

    public function getFieldsValidation($fields)
    {
        $validation = [
            'attributes' => [],
            'rules' => [],
            'error_messages' => [],
            'translation' => [],
        ];

        foreach ($fields as $name => $field) {
            $validation['attributes'][] = $field->getRuleKey();

            if ($rules = $field->getOption('validation.rules', false)) {
                $validation['rules'][$field->getRuleKey()] = $rules;
            }

            if ($messages = $field->getOption('validation.error_messages', false)) {
                $validation['error_messages'][$field->getRuleKey()] = $messages;
            }
        }

        return $validation;
    }

    // OPTIONAL OVERRIDE
    // public function forbiddenResponse()
    // {
        // Optionally, send a custom response on authorize failure
        // (default is to just redirect to initial page with errors)
        //
        // Can return a response, a view, a redirect, or whatever else
        // return Response::make('Permission denied foo!', 403);
    // }

    // OPTIONAL OVERRIDE
    // public function response()
    // {
        // If you want to customize what happens on a failed validation,
        // override this method.
        // See what it does natively here:
        // https://github.com/laravel/framework/blob/master/src/Illuminate/Foundation/Http/FormRequest.php
    // }
}
