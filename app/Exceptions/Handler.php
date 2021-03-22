<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

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
        //GET單筆資料時，報錯誤
        $this->renderable(function (BindingResolutionException $e, $request) {
            return response()->json($response = [
                'success' => false,
                'data' => [],
                'message' => "路由清單中找不到此Controller"], 202);
        });
        $this->renderable(function (ThrottleRequestsException $e, $request) {
            return response()->json($response = [
                'success' => false,
                'data' => [],
                'message' => "請求過多",
            ], 429);
        });
    }
}