<?php

namespace App\Traits;

trait StandardApiResponse
{
    public function success($data, $message = 'Success', $code = 200)
    {
        return response()->json([
            'code' => $code,
            'status' => 'success',
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    protected function error($message = 'Error', $code = 500, $errors = [])
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors
        ], $code);
    }
}
