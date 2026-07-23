<?php

namespace SSD\Test\TestCase\Integrations\Spotify;

use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use SpotifyWebAPI\SpotifyWebAPIAuthException;
use SpotifyWebAPI\SpotifyWebAPIException;
use SSD\Integrations\Spotify\Client;
use SSD\Integrations\Spotify\NotAuthenticatedException;

final class ClientTest extends TestCase
{
    private string $tokensFile;

    protected function setUp(): void
    {
        $this->tokensFile = sys_get_temp_dir() . '/spotify-tokens-test-' . uniqid() . '.json';
    }

    protected function tearDown(): void
    {
        if (file_exists($this->tokensFile)) {
            unlink($this->tokensFile);
        }
    }

    public function testGetAuthorizeUrlContainsScopesAndRedirectUri(): void
    {
        $session = new Session('client-id', 'client-secret', 'https://example.test/spotify-auth');
        $client = new Client($session, $this->createStub(SpotifyWebAPI::class), $this->tokensFile);

        $url = $client->getAuthorizeUrl();

        $this->assertStringContainsString('user-read-playback-state', $url);
        $this->assertStringContainsString('user-read-currently-playing', $url);
        $this->assertStringContainsString('client_id=client-id', $url);
        $this->assertStringContainsString(urlencode('https://example.test/spotify-auth'), $url);
        $this->assertStringNotContainsString('state=', $url);
    }

    public function testGetAuthorizeUrlContainsState(): void
    {
        $session = new Session('client-id', 'client-secret', 'https://example.test/spotify-auth');
        $client = new Client($session, $this->createStub(SpotifyWebAPI::class), $this->tokensFile);

        $this->assertStringContainsString('state=the-secret', $client->getAuthorizeUrl('the-secret'));
    }

    public function testRequestTokensStoresTokensOnSuccess(): void
    {
        $session = $this->createMock(Session::class);
        $session->expects($this->once())->method('requestAccessToken')->with('auth-code')->willReturn(true);
        $session->method('getAccessToken')->willReturn('new-access-token');
        $session->method('getRefreshToken')->willReturn('new-refresh-token');
        $session->method('getTokenExpiration')->willReturn(1_900_000_000);

        $client = new Client($session, $this->createStub(SpotifyWebAPI::class), $this->tokensFile);

        $this->assertTrue($client->requestTokens('auth-code'));
        $this->assertSame(
            ['accessToken' => 'new-access-token', 'refreshToken' => 'new-refresh-token', 'tokenExpiration' => 1_900_000_000],
            $this->readTokensFile()
        );
    }

    public function testRequestTokensDoesNotStoreTokensOnFailure(): void
    {
        $session = $this->createStub(Session::class);
        $session->method('requestAccessToken')->willReturn(false);

        $client = new Client($session, $this->createStub(SpotifyWebAPI::class), $this->tokensFile);

        $this->assertFalse($client->requestTokens('bad-code'));
        $this->assertFileDoesNotExist($this->tokensFile);
    }

    public function testThrowsWhenTokensFileIsMissing(): void
    {
        $client = new Client($this->createStub(Session::class), $this->createStub(SpotifyWebAPI::class), $this->tokensFile);

        $this->expectException(NotAuthenticatedException::class);
        $client->getCurrentlyPlayingTrack();
    }

    public function testThrowsWhenRefreshTokenIsMissing(): void
    {
        $this->writeTokensFile(['accessToken' => 'stored-access-token', 'tokenExpiration' => time() + 3600]);

        $client = new Client($this->createStub(Session::class), $this->createStub(SpotifyWebAPI::class), $this->tokensFile);

        $this->expectException(NotAuthenticatedException::class);
        $client->getCurrentlyPlayingTrack();
    }

    public function testThrowsWhenTokensFileIsCorrupt(): void
    {
        file_put_contents($this->tokensFile, 'this is not json');

        $client = new Client($this->createStub(Session::class), $this->createStub(SpotifyWebAPI::class), $this->tokensFile);

        $this->expectException(NotAuthenticatedException::class);
        $client->getCurrentlyPlayingTrack();
    }

    public function testUsesStoredAccessTokenWhenNotExpired(): void
    {
        $this->writeValidTokensFile();

        $session = $this->createMock(Session::class);
        $session->method('getAccessToken')->willReturn('stored-access-token');
        $session->expects($this->once())->method('setAccessToken')->with('stored-access-token');
        $session->expects($this->once())->method('setRefreshToken')->with('stored-refresh-token');
        $session->expects($this->never())->method('refreshAccessToken');

        $track = (object) ['item' => (object) ['name' => 'Test Track']];
        $api = $this->createMock(SpotifyWebAPI::class);
        $api->expects($this->once())->method('setAccessToken')->with('stored-access-token');
        $api->method('getMyCurrentTrack')->willReturn($track);

        $client = new Client($session, $api, $this->tokensFile);

        $this->assertSame($track, $client->getCurrentlyPlayingTrack());
    }

    public function testRefreshesAndStoresTokensWhenAccessTokenExpired(): void
    {
        $this->writeTokensFile(['accessToken' => 'expired-access-token', 'refreshToken' => 'stored-refresh-token', 'tokenExpiration' => time() - 60]);

        $session = $this->createMock(Session::class);
        $session->expects($this->once())->method('refreshAccessToken')->with('stored-refresh-token');
        $session->method('getAccessToken')->willReturn('refreshed-access-token');
        $session->method('getRefreshToken')->willReturn('stored-refresh-token');
        $session->method('getTokenExpiration')->willReturn(1_900_000_000);

        $api = $this->createStub(SpotifyWebAPI::class);
        $api->method('getMyCurrentTrack')->willReturn((object) []);

        (new Client($session, $api, $this->tokensFile))->getCurrentlyPlayingTrack();

        $this->assertSame(
            ['accessToken' => 'refreshed-access-token', 'refreshToken' => 'stored-refresh-token', 'tokenExpiration' => 1_900_000_000],
            $this->readTokensFile()
        );
    }

    public function testThrowsNotAuthenticatedWhenRefreshTokenIsRevoked(): void
    {
        $this->writeTokensFile(['accessToken' => 'expired-access-token', 'refreshToken' => 'revoked-refresh-token', 'tokenExpiration' => time() - 60]);

        $session = $this->createStub(Session::class);
        $session->method('refreshAccessToken')->willThrowException(new SpotifyWebAPIAuthException('Refresh token revoked'));

        $client = new Client($session, $this->createStub(SpotifyWebAPI::class), $this->tokensFile);

        $this->expectException(NotAuthenticatedException::class);
        $client->getCurrentlyPlayingTrack();
    }

    public function testRetriesOnceWhenTheApiThrows(): void
    {
        $this->writeValidTokensFile();

        $track = (object) ['item' => (object) ['name' => 'Test Track']];
        $calls = 0;
        $api = $this->createStub(SpotifyWebAPI::class);
        $api->method('getMyCurrentTrack')->willReturnCallback(function () use (&$calls, $track): object {
            if (++$calls === 1) {
                throw new SpotifyWebAPIException('The access token expired');
            }

            return $track;
        });

        $client = new Client($this->stubSessionWithAccessToken(), $api, $this->tokensFile);

        $this->assertSame($track, $client->getCurrentlyPlayingTrack());
        $this->assertSame(2, $calls);
    }

    public function testReturnsNullWhenTheApiKeepsThrowing(): void
    {
        $this->writeValidTokensFile();

        $api = $this->createStub(SpotifyWebAPI::class);
        $api->method('getMyCurrentTrack')->willThrowException(new SpotifyWebAPIException('Service unavailable'));

        $client = new Client($this->stubSessionWithAccessToken(), $api, $this->tokensFile);

        $this->assertNull($client->getCurrentlyPlayingTrack());
    }

    public function testReturnsNullWhenNothingIsPlaying(): void
    {
        $this->writeValidTokensFile();

        $api = $this->createStub(SpotifyWebAPI::class);
        $api->method('getMyCurrentTrack')->willReturn(''); // Spotify responds with an empty 204 response when nothing is playing

        $client = new Client($this->stubSessionWithAccessToken(), $api, $this->tokensFile);

        $this->assertNull($client->getCurrentlyPlayingTrack());
    }

    /**
     * @param array<mixed> $tokens
     */
    private function writeTokensFile(array $tokens): void
    {
        file_put_contents($this->tokensFile, json_encode($tokens, JSON_THROW_ON_ERROR));
    }

    private function writeValidTokensFile(): void
    {
        $this->writeTokensFile(['accessToken' => 'stored-access-token', 'refreshToken' => 'stored-refresh-token', 'tokenExpiration' => time() + 3600]);
    }

    /**
     * @return array<mixed>
     */
    private function readTokensFile(): array
    {
        return (array) json_decode((string) file_get_contents($this->tokensFile), true);
    }

    private function stubSessionWithAccessToken(): Session&Stub
    {
        $session = $this->createStub(Session::class);
        $session->method('getAccessToken')->willReturn('stored-access-token');

        return $session;
    }
}
