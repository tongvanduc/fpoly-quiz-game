<?php

use Illuminate\Support\Facades\Log;

if (!function_exists('data_when_error')) {
    function data_when_error(Exception $exception)
    {
        Log::error('ERROR', [
            'REQUEST' => request()->all(),
            'EXCEPTION' => $exception,
        ]);

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

if (!function_exists('catchError')) {
    function catchError($data, $message)
    {
        if (($data == null || trim($data) == "")) {
            throw new Exception($message);
        }

        return $data;
    }
}

if (!function_exists('random_avatar')) {
    function random_avatar($str)
    {
        $str = capitalized_case($str); // Chuyển sang dạng Capitalized Case

        $pos = strrpos($str, ' '); // Tìm vị trí của khoảng trắng cuối cùng

        if ($pos !== false) {
            $lastUppercaseLetter = substr($str, $pos + 1, 1); // Lấy ra chữ cái viết hoa cuối cùng
        } else {
            preg_match('/[A-Z]/', $str, $matches);

            $lastUppercaseLetter =  $matches[0];
        }

        return asset('avatars/' . $lastUppercaseLetter . '.png');
    }
}

if (!function_exists('capitalized_case')) {
    function capitalized_case($str)
    {
        return ucwords(strtolower($str));
    }
}
