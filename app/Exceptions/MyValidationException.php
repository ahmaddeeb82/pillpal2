<?php

namespace App\Exceptions;

use App\Helpers\ApiResponse;
use Exception;
use Illuminate\Contracts\Validation\Validator;

class MyValidationException extends Exception {
    protected $validator;

    protected $code = 422;

    public function __construct(Validator $validator) {
        $this->validator = $validator;
    }

    public function render() {

        return ApiResponse::apiSendResponse($this->code, array_values($this->validator->errors()->getMessages())[0][0],array_values($this->validator->errors()->getMessages())[0][0],$this->validator->errors());
        // return a json with desired format
    }
}