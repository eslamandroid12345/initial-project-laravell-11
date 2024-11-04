<?php

use App\Http\Helpers\Http;
use App\Http\Helpers\Response;
use App\Http\Middleware\LocalizeApi;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function () {
            if (app()->isProduction()) {
                Route::group([
                    'prefix' => 'api',
                    'middleware' => ['api', 'localize-api'],
                    'domain' => env('PRODUCTION_API_SUBDOMAIN')
                ], function () {
                    Route::prefix('v1')->group(function () {
                        Route::prefix('website')->group(base_path('routes/api/v1/website.php'));
                        Route::prefix('mobile')->group(base_path('routes/api/v1/mobile.php'));
                        Route::prefix('dashboard')->group(base_path('routes/api/v1/dashboard.php'));
                    });
                });

                Route::middleware('web')
                    ->group(base_path('routes/web.php'));

                Route::middleware('web')
                    ->domain('PRODUCTION_DASHBOARD_SUBDOMAIN')
                    ->group(base_path('routes/dashboard.php'));
            } else {
                Route::group(['prefix' => 'api', 'middleware' => ['api', 'localize-api']], function () {
                    Route::prefix('v1')->group(function () {
                        Route::prefix('website')->group(base_path('routes/api/v1/website.php'));
                        Route::prefix('mobile')->group(base_path('routes/api/v1/mobile.php'));
                        Route::prefix('dashboard')->group(base_path('routes/api/v1/dashboard.php'));
                    });
                });

                Route::middleware('web')
                    ->group(base_path('routes/web.php'));

                Route::middleware('web')
                    ->group(base_path('routes/dashboard.php'));
            }
        },
        commands: __DIR__.'/../routes/console.php',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'localize-api' => LocalizeApi::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return Response::fail(status: $e->getStatusCode(), message: __('messages.No data found'));
            }
        });
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($e instanceof TokenExpiredException) {
                return Response::fail(status: Http::UNAUTHORIZED, message: 'Token expired');
            }

            if ($e instanceof TokenBlacklistedException) {
                return Response::fail(status: Http::UNAUTHORIZED, message: 'Token blacklisted');
            }

            if ($e instanceof TokenInvalidException) {
                return Response::fail(status: Http::UNAUTHORIZED, message: 'Token invalid');
            }

            if ($e instanceof JWTException) {
                return Response::fail(status: Http::UNAUTHORIZED, message: 'JWT error');
            }

            if ($e instanceof AuthenticationException) {
                if ($request->expectsJson()) {
                    return Response::fail(status: Http::UNAUTHORIZED, message: 'Unauthenticated');
                } else {
                    return redirect()->route('auth.login');
                }
            }

            if ($e instanceof ValidationException) {
                $errors = $e->validator->errors()->all();
                if ($request->acceptsHtml() && collect($request->route()->middleware())->contains('web')) {
                    return $request->ajax() ? response()->json($errors, Http::UNPROCESSABLE_ENTITY) : redirect()->back()->withInput($request->validated())->withErrors($errors);
                }

                return Response::fail(message: 'Validation error', data: $errors);
            }
        });
    })->create();
