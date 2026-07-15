<?php

namespace CMIS\Tests\Unit\Http;

use CMIS\Http\Client;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class ClientTest extends TestCase
{
    private function asciiFilename(string $filename): string
    {
        $method = new ReflectionMethod(Client::class, "getAsciiFilename");

        return $method->invoke(new Client(), $filename);
    }

    public function testVerifyStringPathIsForwardedVerbatim(): void
    {
        $client = (new Client())
            ->setOptions(["verify" => "/etc/ssl/ca.pem"])
            ->initialize();

        $this->assertSame("/etc/ssl/ca.pem", $client->getHttpClient()->getConfig("verify"));
    }

    public function testCurlOptionReachesGuzzleConfig(): void
    {
        $curl = [CURLOPT_RESOLVE => ["host:443:1.2.3.4"]];

        $client = (new Client())
            ->setOptions(["curl" => $curl])
            ->initialize();

        $this->assertSame($curl, $client->getHttpClient()->getConfig("curl"));
    }

    public function testBearerTokenSetsAuthorizationHeaderAndMergesCallerHeaders(): void
    {
        $client = (new Client())
            ->setOptions(["headers" => ["X-Custom" => "value"]])
            ->setAuth(null, null, "the-token")
            ->initialize();

        $headers = $client->getHttpClient()->getConfig("headers");

        $this->assertSame("Bearer the-token", $headers["Authorization"]);
        $this->assertSame("value", $headers["X-Custom"]);
    }

    public function testDefaultVerifyIsEnabled(): void
    {
        $client = (new Client())->initialize();

        $this->assertTrue($client->getHttpClient()->getConfig("verify"));
    }

    public function testDeprecatedVerifySSLDisablesVerification(): void
    {
        $client = (new Client())->verifySSL(false)->initialize();

        $this->assertFalse($client->getHttpClient()->getConfig("verify"));
        $this->assertFalse($client->SSLisVerified());
    }

    // -------------------------------------------------------------------------
    // getAsciiFilename (Content-Disposition ASCII fallback)
    // -------------------------------------------------------------------------

    public function testAsciiFilenamePassesPlainAsciiThroughUnchanged(): void
    {
        $this->assertSame("dokument.txt", $this->asciiFilename("dokument.txt"));
    }

    public function testAsciiFilenameResultContainsOnlyAsciiChars(): void
    {
        $result = $this->asciiFilename("Öffnungszeiten_Grüße.pdf");

        $this->assertSame(1, preg_match('/^[\x20-\x7E]*$/', $result));
    }

    public function testAsciiFilenameTransliteratesGermanUmlauts(): void
    {
        if (!function_exists("transliterator_transliterate")) {
            $this->markTestSkipped("intl extension not available");
        }

        $this->assertSame("Strasse_Grosse.pdf", $this->asciiFilename("Straße_Größe.pdf"));
    }
}
