<?php

namespace App\Exceptions;

use App\Trait\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Prophecy\Exception\Doubler\MethodNotFoundException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{

    use ApiResponse;

    protected $dontReport = [
        //
    ];


    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];


    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }


    //------------------------------------------------ render

    public function render($request, Throwable $e)                   // error 404 api | برای خطاهای درون مادل وقتی برنگردونه
    {

        if ($e instanceof ModelNotFoundException) {
            return $this->errorResponse(404, $e->getMessage());
        }

        if ($e instanceof NotFoundHttpException) {                    // به صورت کلی خطای hhtp داد و پیدا نشد
            return $this->errorResponse(404, $e->getMessage());
        }

        if ($e instanceof MethodNotAllowedHttpException) {            // متود اجازه نداد اجرا بشه
            return $this->errorResponse(404, $e->getMessage());
        }

        if ($e instanceof \Exception) {              // متود اجازه نداد اجرا بشه
            return $this->errorResponse(404, $e->getMessage());
        }


        /*if (config('app.debug')) {                                   // بیاد خطاهای واقعی sql و server به کاربر نمایش نده
            return Parent::render($request, $e);
        }*/

        return $this->errorResponse(500, $e->getMessage());            // اگر غیر از بالایی بود خطای dd500
    }


}
