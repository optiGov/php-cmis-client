<?php

namespace CMIS\Tests\Unit\Session;

use CMIS\Http\Client;
use CMIS\Session\Session;
use CMIS\Session\SessionDocumentCommand;
use CMIS\Session\SessionFolderCommand;
use CMIS\Session\SessionOptions;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{
    /** Cached so reflection is only performed once across all tests in this class. */
    private static ?\ReflectionProperty $httpClientProperty = null;

    /**
     * Creates a real Session with a mock Client injected via reflection,
     * so no live HTTP connection is needed.
     *
     * @return array{Session, Client&\PHPUnit\Framework\MockObject\MockObject}
     */
    private function makeSession(): array
    {
        $session = (new Session())
            ->setUrl('http://example.com/cmis/browser')
            ->setRepositoryId('repo-123')
            ->setOptions(new SessionOptions());

        $mockClient = $this->createMock(Client::class);

        if (self::$httpClientProperty === null) {
            self::$httpClientProperty = (new \ReflectionClass(Session::class))->getProperty('httpClient');
            self::$httpClientProperty->setAccessible(true);
        }
        self::$httpClientProperty->setValue($session, $mockClient);

        return [$session, $mockClient];
    }

    // -------------------------------------------------------------------------
    // Command factory methods
    // -------------------------------------------------------------------------

    public function testCreateDocumentReturnsSessionDocumentCommand(): void
    {
        [$session] = $this->makeSession();

        $command = $session->createDocument('doc.txt', 'DONL', 'content');

        $this->assertInstanceOf(SessionDocumentCommand::class, $command);
    }

    public function testCreateDocumentRequestHasCorrectPostFields(): void
    {
        [$session] = $this->makeSession();

        $fields = $session->createDocument('doc.txt', 'DONL', 'content')
            ->getRequest()
            ->getMergedPostFields();

        $this->assertSame('createDocument', $fields['cmisAction']);
        // createDocument adds cmis:objectTypeId at index 0, cmis:name at index 1
        $this->assertSame('cmis:objectTypeId', $fields['propertyId[0]']);
        $this->assertSame('DONL', $fields['propertyValue[0]']);
        $this->assertSame('cmis:name', $fields['propertyId[1]']);
        $this->assertSame('doc.txt', $fields['propertyValue[1]']);
    }

    public function testCreateFolderReturnsSessionFolderCommand(): void
    {
        [$session] = $this->makeSession();

        $command = $session->createFolder('My Folder', 'ONAVO');

        $this->assertInstanceOf(SessionFolderCommand::class, $command);
    }

    public function testCreateFolderRequestHasCorrectPostFields(): void
    {
        [$session] = $this->makeSession();

        $fields = $session->createFolder('My Folder', 'ONAVO')
            ->getRequest()
            ->getMergedPostFields();

        $this->assertSame('createFolder', $fields['cmisAction']);
        $this->assertSame('cmis:objectTypeId', $fields['propertyId[0]']);
        $this->assertSame('ONAVO', $fields['propertyValue[0]']);
        $this->assertSame('cmis:name', $fields['propertyId[1]']);
        $this->assertSame('My Folder', $fields['propertyValue[1]']);
    }

    public function testUpdateDocumentReturnsSessionDocumentCommand(): void
    {
        [$session] = $this->makeSession();

        $this->assertInstanceOf(SessionDocumentCommand::class, $session->updateDocument('obj-123'));
    }

    public function testUpdateDocumentRequestHasObjectIdAndAction(): void
    {
        [$session] = $this->makeSession();

        $fields = $session->updateDocument('obj-123')->getRequest()->getMergedPostFields();

        $this->assertSame('update', $fields['cmisAction']);
        $this->assertSame('obj-123', $fields['objectId']);
    }

    public function testUpdateDocumentAcceptsCustomCmisAction(): void
    {
        [$session] = $this->makeSession();

        $fields = $session->updateDocument('obj-123', 'customAction')->getRequest()->getMergedPostFields();

        $this->assertSame('customAction', $fields['cmisAction']);
    }

    public function testUpdateFolderReturnsSessionFolderCommand(): void
    {
        [$session] = $this->makeSession();

        $this->assertInstanceOf(SessionFolderCommand::class, $session->updateFolder('folder-456'));
    }

    public function testUpdateFolderRequestHasObjectIdAndAction(): void
    {
        [$session] = $this->makeSession();

        $fields = $session->updateFolder('folder-456')->getRequest()->getMergedPostFields();

        $this->assertSame('update', $fields['cmisAction']);
        $this->assertSame('folder-456', $fields['objectId']);
    }

    public function testUpdateFolderAcceptsCustomCmisAction(): void
    {
        [$session] = $this->makeSession();

        $fields = $session->updateFolder('folder-456', 'specialUpdate')->getRequest()->getMergedPostFields();

        $this->assertSame('specialUpdate', $fields['cmisAction']);
    }

    public function testUpdateDocumentContentReturnsSessionDocumentCommand(): void
    {
        [$session] = $this->makeSession();

        $this->assertInstanceOf(SessionDocumentCommand::class, $session->updateDocumentContent('obj-123', 'doc.txt', 'new content'));
    }

    public function testUpdateDocumentContentRequestHasSetContentAction(): void
    {
        [$session] = $this->makeSession();

        $fields = $session->updateDocumentContent('obj-123', 'doc.txt', 'new content')
            ->getRequest()
            ->getMergedPostFields();

        $this->assertSame('setContent', $fields['cmisAction']);
        $this->assertSame('obj-123', $fields['objectId']);
    }

    // -------------------------------------------------------------------------
    // getRepositoryInfo
    // -------------------------------------------------------------------------

    public function testGetRepositoryInfoReturnsDataForConfiguredRepository(): void
    {
        [$session, $mockClient] = $this->makeSession();

        $repoData = [
            'repo-123'   => ['repositoryId' => 'repo-123', 'repositoryName' => 'Test Repository'],
            'other-repo' => ['repositoryId' => 'other-repo', 'repositoryName' => 'Other Repository'],
        ];

        $mockClient->method('get')->willReturn(new Response(200, [], json_encode($repoData)));

        $info = $session->getRepositoryInfo();

        $this->assertSame('repo-123', $info['repositoryId']);
        $this->assertSame('Test Repository', $info['repositoryName']);
        $this->assertArrayNotHasKey('other-repo', $info);
    }

    public function testGetRepositoryInfoReturnsEmptyArrayWhenRepositoryNotFound(): void
    {
        [$session, $mockClient] = $this->makeSession();

        $mockClient->method('get')
            ->willReturn(new Response(200, [], json_encode(['some-other-repo' => ['repositoryId' => 'some-other-repo']])));

        $this->assertSame([], $session->getRepositoryInfo());
    }

    public function testGetRepositoryInfoCallsGetOnBaseUrl(): void
    {
        [$session, $mockClient] = $this->makeSession();

        $mockClient->expects($this->once())
            ->method('get')
            ->willReturn(new Response(200, [], json_encode([])));

        $session->getRepositoryInfo();
    }
}
