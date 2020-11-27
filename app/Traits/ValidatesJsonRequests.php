<?php
namespace App\Traits;

use Illuminate\Http\Request;

trait ValidatesJsonRequests {

    /**
     * Validate the given request with the given rules.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $rules
     * @param  array  $messages
     * @param  array  $customAttributes
     * @return array
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validateJson(Request $request, array $rules, array $messages = [], array $customAttributes = []) {
        return $this->getValidationFactory()->make(
            $request->json()->all(), $rules, $messages, $customAttributes
        )->validate();
    }
}
