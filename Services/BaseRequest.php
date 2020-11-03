<?php

namespace App\Services;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        $rules = [];
        switch ($this->method())
        {
            case 'POST':
                $rules = $this->rulesPost();
                break;
            case 'PUT':
                $rules = $this->rulesPut();
                break;
        }

        return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        $message = implode("<br>", $validator->errors()->all());
        throw new HttpResponseException(response()->json(['success' => false, 'message' => $message], 422));
    }

    /**
     * @return array
     */
    public function rulesPost(): array
    {
        return[
        ];
    }

    /**
     * @return array
     */
    public function rulesPut(): array
    {
        return [
        ];
    }

}
