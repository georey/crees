<?php
require 'vendor/autoload.php';

// Cargar las variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Usar las credenciales del .env
$provider = new League\OAuth2\Client\Provider\Google([
    'clientId'     => $_ENV['GOOGLE_CLIENT_ID'],
    'clientSecret' => $_ENV['GOOGLE_CLIENT_SECRET'],
    'redirectUri'  => 'http://localhost',
    'accessType'   => 'offline',
    'scope'        => ['https://mail.google.com/'],
]);

if (!isset($_GET['code'])) {
    $authUrl = $provider->getAuthorizationUrl();
    echo "Abre esta URL en tu navegador:\n$authUrl\n";
    $_SESSION['oauth2state'] = $provider->getState();
} else {
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    echo "Access Token: " . $token->getToken() . "\n";
    echo "Refresh Token: " . $token->getRefreshToken() . "\n";
}
