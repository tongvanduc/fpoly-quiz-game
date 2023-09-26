<?php

use Illuminate\Support\Facades\Log;

if (!function_exists('data_when_error')) {
    function data_when_error(Exception $exception)
    {
        Log::error('Exception', [$exception]);

        if (app()->environment('production')) {
            return [];
        }

        return [
            'code' => $exception->getCode(),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ];
    }
}

if (!function_exists('is_super_admin')) {
    function is_super_admin($user = null)
    {
        $user = $user ?? auth()->user();

        return $user->type_user === TYPE_USER_SUPER_ADMIN;
    }
}
