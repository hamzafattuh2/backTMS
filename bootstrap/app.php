<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    // ✅ اجمع كل الـ aliases هنا مرة واحدة
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            // صلاحيات المستخدمين
            'is_admin' => \App\Http\Middleware\IsAdmin::class,
            'is_guide' => \App\Http\Middleware\IsGuide::class,
            'is_tourist' => \App\Http\Middleware\IsTourist::class,
            'language_match' => \App\Http\Middleware\GuideLanguageMatchesTrip::class,
            'check_suggestion_dates' => \App\Http\Middleware\CheckGuideSuggestionDates::class,
            // تأكيد كلمة المرور الحالية
            'confirm_password' => \App\Http\Middleware\ConfirmCurrentPassword::class,

        ]);
    })

    ->withExceptions(function (Exceptions $exceptions) {
        // يمكنك وضع تخصيص معالِج الاستثناءات هنا إذا رغبت
    })

    ->create();
