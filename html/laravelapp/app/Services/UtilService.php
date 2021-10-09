<?php

namespace App\Services;

use Illuminate\Http\Exceptions\HttpResponseException;

class UtilService
{
    public static function getIp()
    {
        $keys = [
            'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'
        ];
        foreach ($keys as $key) {
            if (array_key_exists($key, $_SERVER)) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var(
                        $ip,
                        FILTER_VALIDATE_IP,
                        FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
                    )) {
                        return $ip;
                    }
                }
            }
        }
        return 'ip不明';
    }

    public static function throwHttpResponseException($errors, $status = 400)
    {
        $res = response()->json([
            'status' => $status,
            'message' => $errors,
        ], $status);

        throw new HttpResponseException($res);
    }
}
