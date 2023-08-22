<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule as Rule;

class CustomFileRule implements Rule
{
    protected array $acceptableTypes = [];

    public function __construct(array $acceptableTypes = [])
    {
        $this->acceptableTypes = $acceptableTypes;
    }

    /**
     * @param string $attribute
     * @param \Illuminate\Http\UploadedFile $value
     *
     * @return bool
     */
    public function validate($attribute, $value, Closure $fail) : void
    {
        if(!in_array($value->getClientOriginalExtension(), $this->acceptableTypes)) 
        {
            $fail('Invalid document type uploaded');
        }
    }
}