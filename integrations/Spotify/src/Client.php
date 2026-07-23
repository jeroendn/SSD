<?php

namespace SSD\Integrations\Spotify;

use JsonException;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use SpotifyWebAPI\SpotifyWebAPIAuthException;
use SpotifyWebAPI\SpotifyWebAPIException;

final class Client
{
    private const array SCOPES = ['user-read-playback-state', 'user-read-currently-playing'];
    private const string TOKENS_FILE = __DIR__ . '/../../../config/spotify-tokens.json';

    private readonly Session $session;
    private readonly SpotifyWebAPI $api;
    private readonly string $tokensFile;
    private bool $loggedIn = false;

    /**
     * All parameters default to the production dependencies and only exist for injection in unit tests.
     * @param Session|null $session
     * @param SpotifyWebAPI|null $api
     * @param string|null $tokensFile
     */
    public function __construct(?Session $session = null, ?SpotifyWebAPI $api = null, ?string $tokensFile = null)
    {
        $this->session = $session ?? new Session(
            API_SPOTIFY_CLIENT_ID,
            API_SPOTIFY_CLIENT_SECRET,
            BASE_URL . '/spotify-auth' // Must be registered as redirect URI in the Spotify application dashboard
        );
        $this->api = $api ?? new SpotifyWebAPI();
        $this->tokensFile = $tokensFile ?? self::TOKENS_FILE;
    }

    /**
     * The URL to visit to authorize the application. Spotify redirects back to the redirect URI with a "code" query
     * parameter, which can then be passed to requestTokens(). Used by views/spotify-auth.php.
     * @param string|null $state Echoed back by Spotify in the redirect, used to protect the callback endpoint
     * @return string
     */
    public function getAuthorizeUrl(?string $state = null): string
    {
        $options = ['scope' => self::SCOPES];

        if ($state !== null) {
            $options['state'] = $state;
        }

        return $this->session->getAuthorizeUrl($options);
    }

    /**
     * Exchange an authorization code for access and refresh tokens and store them. Used by views/spotify-auth.php.
     * @param string $authorizationCode
     * @return bool Success status
     * @throws JsonException
     */
    public function requestTokens(string $authorizationCode): bool
    {
        return $this->session->requestAccessToken($authorizationCode) && $this->storeSessionTokens();
    }

    /**
     * @return void
     * @throws JsonException
     */
    private function login(): void
    {
        $tokens = $this->getTokens();

        $accessToken = $tokens['accessToken'] ?? null;
        $refreshToken = $tokens['refreshToken'] ?? null;
        $tokenExpiration = $tokens['tokenExpiration'] ?? 0;

        if (!is_string($refreshToken) || $refreshToken === '') {
            throw new NotAuthenticatedException('Spotify is not authenticated. Visit /spotify-auth to authorize.');
        }

        if (is_string($accessToken) && is_int($tokenExpiration) && $tokenExpiration > time()) { // If we have a token, and it's not expired, use it
            $this->session->setAccessToken($accessToken);
            $this->session->setRefreshToken($refreshToken);
        } else {
            try {
                $this->session->refreshAccessToken($refreshToken);
            } catch (SpotifyWebAPIAuthException $e) { // The refresh token was revoked or is otherwise no longer accepted
                throw new NotAuthenticatedException('Spotify authorization was revoked. Visit /spotify-auth to re-authorize.', previous: $e);
            }
            if (!$this->storeSessionTokens()) { // Try one more time on failure
                sleep(1); // Wait a second, before try again
                $this->storeSessionTokens();
            }
        }

        $this->api->setAccessToken($this->session->getAccessToken());
        $this->loggedIn = true;
    }

    /**
     * Get the current access and refresh tokens from a json file.
     * @return array<mixed> Not shaped, the file contents may be anything
     */
    private function getTokens(): array
    {
        $fileContents = @file_get_contents($this->tokensFile);

        if (!$fileContents) {
            return [];
        }

        return (array) json_decode($fileContents, true);
    }

    /**
     * Store the session's current access and refresh tokens to a json file.
     * @return bool Success status
     * @throws JsonException
     */
    private function storeSessionTokens(): bool
    {
        $json = [
            'accessToken' => $this->session->getAccessToken(),
            'refreshToken' => $this->session->getRefreshToken(),
            'tokenExpiration' => $this->session->getTokenExpiration(),
        ];

        return (bool) file_put_contents($this->tokensFile, json_encode($json, JSON_THROW_ON_ERROR), LOCK_EX); // Lock to prevent torn writes by concurrent widget refreshes
    }

    /**
     * @return object|null
     * @throws JsonException
     */
    public function getCurrentlyPlayingTrack(): ?object
    {
        if (!$this->loggedIn) {
            $this->login();
        }

        try {
            $currentlyPlayingTrack = $this->api->getMyCurrentTrack();
        } catch (SpotifyWebAPIException) {
            $this->login(); // The access token may just have expired, retry once with a fresh login

            try {
                $currentlyPlayingTrack = $this->api->getMyCurrentTrack();
            } catch (SpotifyWebAPIException) {
                return null;
            }
        }

        return is_object($currentlyPlayingTrack) ? $currentlyPlayingTrack : null;
    }
}
