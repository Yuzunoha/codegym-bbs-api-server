<?php

namespace App\Services;

use Illuminate\Http\Exceptions\HttpResponseException;

class UtilService
{
    public function getIp(): string
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

    public function throwHttpResponseException($message, int $status = 400): void
    {
        $res = response()->json([
            'status' => $status,
            'message' => $message,
        ], $status);

        throw new HttpResponseException($res);
    }
}
