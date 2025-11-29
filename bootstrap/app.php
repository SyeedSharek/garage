<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->report(function (Throwable $e, Request $request = null) {
            $context = [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
            ];

            // Add request information if available
            if ($request) {
                $context['request'] = [
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'route' => $request->route()?->getName(),
                    'route_params' => $request->route()?->parameters(),
                ];

                // Add request data (sanitized - exclude sensitive fields)
                $requestData = $request->except(['password', 'password_confirmation', '_token', 'api_token']);
                if (!empty($requestData)) {
                    $context['request']['data'] = $requestData;
                }
            }

            // Add authenticated user information
            if (Auth::check()) {
                $user = Auth::user();
                if ($user) {
                    $context['user'] = [
                        'id' => $user->id,
                        'email' => $user->email,
                        'name' => $user->name ?? null,
                    ];
                }
            }

            // Add memory usage information
            $formatBytes = function($bytes, $precision = 2) {
                $units = ['B', 'KB', 'MB', 'GB', 'TB'];
                for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
                    $bytes /= 1024;
                }
                return round($bytes, $precision) . ' ' . $units[$i];
            };

            $context['memory'] = [
                'usage' => memory_get_usage(true),
                'usage_formatted' => $formatBytes(memory_get_usage(true)),
                'peak' => memory_get_peak_usage(true),
                'peak_formatted' => $formatBytes(memory_get_peak_usage(true)),
                'limit' => ini_get('memory_limit'),
            ];

            // Add execution time if available
            if (defined('LARAVEL_START')) {
                $context['execution_time'] = round((microtime(true) - LARAVEL_START) * 1000, 2) . 'ms';
            }

            // Add database query information for database-related errors
            if ($e instanceof \Illuminate\Database\QueryException) {
                $context['database'] = [
                    'sql' => $e->getSql(),
                    'bindings' => $e->getBindings(),
                    'connection' => $e->getConnectionName(),
                ];
            }

            // Add additional context for timeout errors
            if (str_contains($e->getMessage(), 'Maximum execution time') ||
                str_contains($e->getMessage(), 'timeout')) {
                $context['timeout'] = [
                    'max_execution_time' => ini_get('max_execution_time'),
                    'set_time_limit' => function_exists('set_time_limit'),
                ];
            }

            // Log with appropriate level
            if ($e instanceof \Symfony\Component\ErrorHandler\Error\FatalError) {
                Log::critical('Exception occurred', $context);
            } elseif ($e instanceof \Illuminate\Database\QueryException) {
                Log::error('Database query exception', $context);
            } else {
                Log::error('Exception occurred', $context);
            }
        });
    })->create();

/**
 * Format bytes to human-readable format
 */
if (!function_exists('formatBytes')) {
    function formatBytes($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
