<?php

namespace CMIS\Tests\Unit\Session;

use CMIS\Http\Client;
use CMIS\Http\Request;
use CMIS\Session\Session;
use CMIS\Session\SessionDocumentCommand;
use CMIS\Session\SessionFolderCommand;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Shared infrastructure for SessionDocumentCommand and SessionFolderCommand tests.
 */
abstract class CmisCommandTestCase extends TestCase
{
    /**
     * Wraps a CMIS properties map in the full server response envelope.
     */
    protected function makeCmisResponse(array $properties): Response
    {
        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode(['properties' => $properties])
        );
    }

    /**
     * Creates a mock HTTP Client that returns the given response on post().
     */
    protected function makeMockClient(Response $response): Client
    {
        $mockClient = $this->createMock(Client::class);
        $mockClient->method('post')->willReturn($response);
        return $mockClient;
    }

    /**
     * Creates a mock Session that returns the given Client from getHttpClient().
     */
    protected function makeMockSession(Client $mockClient): Session
    {
        $mockSession = $this->createMock(Session::class);
        $mockSession->method('getHttpClient')->willReturn($mockClient);
        return $mockSession;
    }

    protected function makeDocumentCommand(Response $response): SessionDocumentCommand
    {
        return new SessionDocumentCommand(
            $this->makeMockSession($this->makeMockClient($response)),
            new Request('http://example.com/cmis/browser/repo/root')
        );
    }

    protected function makeFolderCommand(Response $response): SessionFolderCommand
    {
        return new SessionFolderCommand(
            $this->makeMockSession($this->makeMockClient($response)),
            new Request('http://example.com/cmis/browser/repo/root')
        );
    }
}
