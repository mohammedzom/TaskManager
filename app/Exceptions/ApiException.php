<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\App; // لاستخدام App::hasDebugModeEnabled() أو config

class ApiException extends Exception
{
    protected $code;

    protected $details;

    public function __construct($message, $details = null, $code = 400)
    {
        parent::__construct($message);
        $this->code = $code;
        $this->details = $details;
    }

    public function render($request)
    {
        $response = [
            'status' => false,
            'message' => $this->getMessage(),
        ];

        if (config('app.debug')) {
            if ($this->details) {
                $response['details'] = $this->details;
            }
        } else {
            if (is_array($this->details) && ! isset($this->details['errorInfo'])) {
                $response['details'] = $this->details;
            }
        }

        return response()->json($response, $this->code);
    }
}
