<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use function App\utilities\cleanCardNumber;
use function App\utilities\isCardValid;
use function App\utilities\toEnglishDigits;

class TransferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'from_card' => 'required|string|size:16',
            'to_card'   => 'required|string|size:16|different:from_card',
            'amount'     => 'required|integer|min:1000|max:5000000'
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'from_card' => cleanCardNumber($this->from_card) ,
            'to_card'   => cleanCardNumber($this->to_card) ,
            'amount'    => toEnglishDigits($this->amount)
        ]);
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!isCardValid($this->from_card)) {
                $validator->errors()->add('from_card', 'شماره کارت مبدا نامعتبر است.');
            }
            if (!isCardValid($this->to_card)) {
                $validator->errors()->add('to_card', 'شماره کارت مقصد نامعتبر است.');
            }
        });
    }

    public function dto() :array
    {
        return [
            'from_card' => $this->from_card ,
            'to_card'   => $this->to_card ,
            'amount'    => $this->amount
        ];
    }
}
