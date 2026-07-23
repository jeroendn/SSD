<?php

/**
 * (Re)authorize the Spotify integration.
 *
 * Visiting /spotify-auth?<url-secret> redirects to Spotify's consent page. Spotify then redirects back to this
 * same endpoint with a "code" parameter, which is exchanged for tokens automatically. Because Spotify calls this
 * endpoint without the URL secret, the secret is passed through the OAuth "state" parameter instead, which
 * Spotify echoes back in the redirect.
 */

use SpotifyWebAPI\SpotifyWebAPIException;
use SSD\Integrations\Spotify\Client;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../php/functions.php';

$state = $_GET['state'] ?? null;
$code = $_GET['code'] ?? null;
$error = $_GET['error'] ?? null;

if (!authorized() && $state !== URL_SECRET_VALUE) {
    echo 'Unauthorized!';
    exit;
}

$client = new Client();

if (!is_string($code) && $error === null) { // Step 1: send the user to Spotify's consent page
    header('Location: ' . $client->getAuthorizeUrl(URL_SECRET_VALUE));
    exit;
}

// Step 2: Spotify redirected back, exchange the authorization code for tokens
$success = false;
$message = 'Authorization was denied or failed';

if (is_string($code) && $code !== '') {
    try {
        $success = $client->requestTokens($code);
        $message = $success ? 'Spotify authorized successfully' : 'Token exchange failed';
    } catch (SpotifyWebAPIException $e) {
        $message = 'Token exchange failed: ' . h($e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="EN" dir="ltr">
<head>
    <title>SS Dashboard - Spotify authorization</title>
    <meta name="robots" content="noindex"/>
</head>

<body>

<p><?= $message ?></p>
<p><a href="/dashboard?<?= h(URL_SECRET_KEY . '=' . URL_SECRET_VALUE) ?>">Back to dashboard</a></p>

</body>
</html>
