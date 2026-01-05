<?php

namespace App\Services;

use Swift_SmtpTransport;
use League\OAuth2\Client\Provider\Google;
use Illuminate\Support\Facades\Log;

class OAuthTransportFactory
{
    public static function make()
    {
        try {
            $provider = new Google([
                'clientId' => env('GOOGLE_CLIENT_ID'),
                'clientSecret' => env('GOOGLE_CLIENT_SECRET'),
            ]);

            Log::info('Intentando obtener access token con refresh token...');
            
            $accessToken = $provider->getAccessToken('refresh_token', [
                'refresh_token' => env('GOOGLE_REFRESH_TOKEN'),
            ]);

            Log::info('Access token obtenido exitosamente');

            return (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
                ->setUsername(env('MAIL_USERNAME'))
                ->setPassword($accessToken->getToken());
        } catch (\Exception $e) {
            Log::error('Error en OAuthTransportFactory: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }
}
