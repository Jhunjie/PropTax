<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        $this->configureDefaults();
        $this->configureEmailVerification();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }

    /**
     * Wire up email verification: make sure the "Registered" event actually
     * sends the verification link, and give that email the same branded
     * tone as the app's other notifications (see AccountStatusUpdated).
     */
    protected function configureEmailVerification(): void
    {
        Event::listen(Registered::class, SendEmailVerificationNotification::class);

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            $greeting = 'Hi'.($notifiable->name ? " {$notifiable->name}" : '').',';

            return (new MailMessage)
                ->subject('Verify your email address')
                ->greeting($greeting)
                ->line('Thanks for registering with '.config('app.name').'. Please confirm this is your email address to continue setting up your account.')
                ->action('Verify email address', $url)
                ->line('This link will expire in 60 minutes.')
                ->line("If you didn't create this account, no further action is needed.");
        });
    }
}
