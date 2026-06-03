<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CartController;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') !== 'local') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        View::composer('layouts.app', function ($view) {
            $notifications = [];
            $unreadCount = 0;
            $cartCount = 0;
            if (Auth::check()) {
                $ma_nguoi_dung = Auth::id();
                $notificationsData = app(NotificationController::class)->getNotification($ma_nguoi_dung);
                $notifications = $notificationsData['notifications'];
                $unreadCount = $notificationsData['unreadCount'];
                $cartCount = app(CartController::class)->cartCount($ma_nguoi_dung);
                
            }

            $view->with([
                'notifications' => $notifications,
                'unreadCount' => $unreadCount,
                'cartCount' => $cartCount,
            ]);
        });

        Blade::directive('vnd', function ($expression) {
            return "<?php echo format_vnd($expression); ?>";
        });

        Mail::extend('brevo', function () {
        return (new BrevoTransportFactory())->create(
            new Dsn(
                'brevo+api',
                'default',
                config('services.brevo.key')
            )
        );
    });
    }
}
