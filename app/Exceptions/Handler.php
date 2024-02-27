<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Exception;
use PhpParser\Node\Stmt\Return_;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (TokenBlacklistedException $e, $request) {
            return response(['error' => 'Token não pode ser utilizado'], Response::HTTP_BAD_REQUEST);
        });

        $this->renderable(function (TokenInvalidException $e, $request) {
            return response(['error' => 'Token inválido'], Response::HTTP_BAD_REQUEST);
        });

        $this->renderable(function (TokenExpiredException $e, $request) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return response(['error' => 'Seu Token expirou'], Response::HTTP_BAD_REQUEST);
            // return redirect()->route('login');
        });

        $this->renderable(function (JWTException $e, $request) {
            return response(['error' => 'Token não fornecido'], Response::HTTP_BAD_REQUEST);
        });


        // $this->reportable(function (Throwable $e) {
        //     //
        // });
    }
}
