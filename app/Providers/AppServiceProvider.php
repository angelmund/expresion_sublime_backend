<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        
        VerifyEmail::createUrlUsing(function (object $notifiable) {
            $frontendUrl = config('app.frontend_url'); 
            $verifyUrl = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(Config::get('auth.verification.expire', 60)),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );

           
            return $frontendUrl . '/verify-email?url=' . urlencode($verifyUrl);
        });

        
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Bienvenido a Expresión Sublime - Verifica tu correo')
                ->greeting('¡Hola, ' . $notifiable->name . '!')
                ->line('Gracias por registrarte. Por favor haz clic en el botón de abajo para verificar tu cuenta y acceder al panel de control.')
                ->action('Verificar mi cuenta', $url)
                ->line('Si no creaste esta cuenta, puedes ignorar este correo de forma segura.')
                ->salutation('El equipo de Expresión Sublime');
        });

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            $frontendUrl = config('app.frontend_url');
            $email = urlencode($notifiable->getEmailForPasswordReset());
            
            return $frontendUrl . '/reset-password/' . $token . '?email=' . $email;
        });

        
        ResetPassword::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Recuperación de contraseña - Expresión Sublime')
                ->greeting('¡Hola!')
                ->line('Recibes este correo porque solicitaste un restablecimiento de contraseña para tu cuenta.')
                ->action('Restablecer Contraseña', $url)
                ->line('Si tienes problemas con el botón, copia y pega el siguiente enlace en tu navegador:')
                ->line($url)
                ->line('Este enlace expirará en 60 minutos.')
                ->line('Si no solicitaste este cambio, no es necesario realizar ninguna acción.')
                ->salutation('El equipo de Expresión Sublime');
        });
    }
}