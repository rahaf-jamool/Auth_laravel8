<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Traits\GlobalTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Throwable;

class Handler extends ExceptionHandler
{ 
    use GlobalTrait;
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
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
        $this->reportable(function (Throwable $e) {
        });
        $this->renderable(function (Throwable $e) {
                       return $this->handleException($e);
                   });
                }
               public function handleException( Throwable $e){
                     if ($e instanceof ModelNotFoundException) {
                       $model = strtolower(class_basename($e->getModel()));
                       return $this->errorResponse("Does not exist any instance of {$model} with the given id", Response::HTTP_NOT_FOUND);
                   } 
                   else if ($e instanceof AuthorizationException) {
                       return $this->errorResponse($e->getMessage(),$e->getCode(),Response::HTTP_FORBIDDEN);
                   } 
                else if ($e instanceof TokenBlacklistedException) {
                       return $this->errorResponse($e->getMessage(), Response::HTTP_UNAUTHORIZED);
                   } else if ($e instanceof AuthenticationException) {
                       return $this->errorResponse($e->getMessage(), Response::HTTP_UNAUTHORIZED);
                   } else if ($e instanceof ValidationException) {
                       $errors = $e->validator->errors()->getMessages();
                       return $this->errorResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
                   } else if ($e instanceof RouteNotFoundException) {
                       $errors = $e->getMessage();
                       return $this->errorResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
                   }else if ($e instanceof TokenInvalidException) {
                       $errors = $e->getMessage();
                       return $this->errorResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
                   }else if ($e instanceof TokenExpiredException) {
                       $errors = $e->getMessage();
                       return $this->errorResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
                   }else if ($e instanceof JWTException) {
                       $errors = $e->getMessage();
                       return $this->errorResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
                   }
                   else {
                       if (config('app.debug'))
                           return $this->errorResponse($e->getMessage(),403);
                       else {
                           return $this->errorResponse('Try later', Response::HTTP_INTERNAL_SERVER_ERROR);
                       }
                   }
               }
            }
