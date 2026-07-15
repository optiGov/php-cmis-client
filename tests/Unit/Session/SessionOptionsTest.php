<?php

namespace CMIS\Tests\Unit\Session;

use CMIS\Session\SessionOptions;
use PHPUnit\Framework\TestCase;

class SessionOptionsTest extends TestCase
{
    public function testDefaultConstructorEnablesVerify(): void
    {
        $options = new SessionOptions();

        $this->assertSame(["verify" => true], $options->getOptions());
        $this->assertTrue($options->getOption("verify"));
    }

    public function testConstructorAcceptsCustomOptions(): void
    {
        $bag = [
            "verify"  => "/path/to/ca.pem",
            "curl"    => [CURLOPT_RESOLVE => ["host:443:1.2.3.4"]],
            "timeout" => 12,
        ];

        $options = new SessionOptions($bag);

        $this->assertSame($bag, $options->getOptions());
    }

    public function testSetOptionOverridesAndAdds(): void
    {
        $options = (new SessionOptions())
            ->setOption("verify", false)
            ->setOption("timeout", 5);

        $this->assertFalse($options->getOption("verify"));
        $this->assertSame(5, $options->getOption("timeout"));
    }

    public function testSetOptionAcceptsCaBundlePathString(): void
    {
        $options = (new SessionOptions())->setOption("verify", "/etc/ssl/ca.pem");

        $this->assertSame("/etc/ssl/ca.pem", $options->getOption("verify"));
    }

    public function testGetOptionReturnsNullForMissingKey(): void
    {
        $this->assertNull((new SessionOptions())->getOption("does-not-exist"));
    }
}
