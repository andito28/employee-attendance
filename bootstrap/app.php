<?php

use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Middleware\EnsureApiTokenIsValid;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Services\Constant\Attendance\WeeklyDayOffConstant;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(using: function () {
        $namespace = 'App\\Http\\Controllers';

        $version = config('base.conf.version');
        $service = config('base.conf.service');

        Route::match(['get', 'post'], 'testing', "$namespace\\Controller@testing");

        Route::prefix(config('base.conf.prefix.web') . "/$version/$service")
            ->middleware(['web'])
            ->namespace("$namespace\\" . config('base.conf.namespace.web'))
            ->group(base_path('routes/web.php'));

        Route::prefix(config('base.conf.prefix.mobile') . "/$version/$service")
            ->middleware(['web'])
            ->namespace("$namespace\\" . config('base.conf.namespace.mobile'))
            ->group(base_path('routes/mobile.php'));

        Route::prefix(config('base.conf.prefix.mygx') . "/$version/$service")
            ->middleware(['web'])
            ->namespace("$namespace\\" . config('base.conf.namespace.mygx'))
            ->group(base_path('routes/mygx.php'));
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: ['api/*']);
        $middleware->alias(
            [
                'auth.api' => EnsureApiTokenIsValid::class,
                'role' => CheckRole::class,
            ],
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->withSchedule(function () {
        Schedule::command('app:update-resignation-status-command')->daily();
        Schedule::command('app:set-weekly-day-off-command')->yearlyOn(
        WeeklyDayOffConstant::MONTH,WeeklyDayOffConstant::DAY, '01:00');
        Schedule::command('dev-test')->everyMinute();
    })->create();
