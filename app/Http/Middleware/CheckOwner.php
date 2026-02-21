<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class CheckOwner
{
    public function handle(Request $request, Closure $next, $model)
    {
        $user = auth()->user();
        
        // ادمین‌ها همیشه دسترسی دارند
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        $resourceId = $request->route($model);
        
        if (!$resourceId) {
            abort(404);
        }

        // بررسی مالکیت
        if ($model == 'investment') {
            $investment = $resourceId;
            if ($investment->investor->user_id != $user->id) {
                abort(403, 'شما به این سرمایه‌گذاری دسترسی ندارید.');
            }
        }

        if ($model == 'investor') {
            $investor = $resourceId;
            if ($investor->user_id != $user->id) {
                abort(403, 'شما به این سرمایه‌گذار دسترسی ندارید.');
            }
        }

        return $next($request);
    }
}
