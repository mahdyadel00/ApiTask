<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function sendResponse($result, $message)
    {
        $response = [
            'status' => 'success',
            'data' => $result,
            'message' => $message,
        ];
        return response()->json($response, 200);
    }

    protected function sendError($error, $errorMessages = [], $code = 200)
    {
        $response = [
            'status' => 'error',
            'message' => $error,
            'data'   => null
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }
}
