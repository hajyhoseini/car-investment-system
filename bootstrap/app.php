<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckRole;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // ثبت middlewareهای مستعار (alias) برای استفاده در route‌ها
        $middleware->alias([
            'role' => CheckRole::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
        
        // می‌تونی middlewareهای گروهی رو هم اضافه کنی (اختیاری)
        // $middleware->group('web', [
        //     \Illuminate\Cookie\Middleware\EncryptCookies::class,
        //     \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        //     \Illuminate\Session\Middleware\StartSession::class,
        //     \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        //     \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
        //     \Illuminate\Routing\Middleware\SubstituteBindings::class,
        //     // middlewareهای سفارشی خودت
        // ]);
        
        // middlewareهای پیش‌فرض (اگه نیاز به تغییر داری)
        // $middleware->append(\App\Http\Middleware\SomeMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // مدیریت خطاها (اختیاری)
        // $exceptions->render(function (Throwable $e) {
        //     return response()->view('errors.custom', [], 500);
        // });
    })->create();