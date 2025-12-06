<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiException extends Exception
{
    protected $httpCode;

    protected $details;

    protected $internalCode;

    public function __construct($message, $details = null, $httpCode = 400, $internalCode = null)
    {
        parent::__construct($message);

        $this->details = $details;
        $this->httpCode = $httpCode;
        $this->internalCode = $internalCode;
    }

    public function render(Request $request): JsonResponse
    {
        $response = [
            'status' => false,
            'message' => $this->getMessage(),
        ];

        if ($this->internalCode) {
            $response['code'] = $this->internalCode;
        }

        if ($this->shouldShowDetails()) {
            $response['details'] = $this->details;
        }

        return response()->json($response, $this->httpCode);
    }

    private function shouldShowDetails(): bool
    {
        if (empty($this->details)) {
            return false;
        }
        if (config('app.debug')) {
            return true;
        }

        if (is_array($this->details) && ! isset($this->details['errorInfo'])) {
            return true;
        }

        return false;
    }
}
