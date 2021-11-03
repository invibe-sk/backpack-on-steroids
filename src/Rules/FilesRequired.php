<?php

namespace Invibe\BackpackOnSteroids\Rules;

use Illuminate\Contracts\Validation\Rule;

class FilesRequired implements Rule
{
    private $message;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $message = null)
    {
        $this->message = $message ?? "Vyberte súbor";
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return !empty(data_get(json_decode($value, true), "files", []));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
