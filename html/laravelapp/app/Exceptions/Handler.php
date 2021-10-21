<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (Throwable $e) {
            $status = $this->_getStatusCode($e);
            return response()->json([
                'status' => $status,
                'message' => $e->getMessage()
            ], $status);
        });
    }

    /**
     * ステータスコードを決める
     */
    private function _getStatusCode(Throwable $e): int
    {
        if ($e instanceof AuthenticationException) {
            $status = 401;
        } else {
            $status = intval($e->getCode()) ?: 400;
        }
        return $status;
    }
}
