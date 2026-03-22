<?php

namespace CMIS\Tests\Unit\Entities;

use CMIS\Entities\Document;
use CMIS\Http\Client;
use CMIS\Http\Request;
use CMIS\Session\Session;
use CMIS\Session\SessionDocumentCommand;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class DocumentTest extends TestCase
{
    private function makeDocument(Session $session, string $objectId = 'doc-obj-123'): Document
    {
        return (new Document($session))
            ->setObjectId($objectId)
            ->setName('test.txt')
            ->setCreationDate(1700000000)
            ->setCreatedBy('john.doe');
    }

    // -------------------------------------------------------------------------
    // updateProperties (new)
    // -------------------------------------------------------------------------

    public function testUpdatePropertiesReturnsSessionDocumentCommand(): void
    {
        $mockCommand = $this->createMock(SessionDocumentCommand::class);

        $mockSession = $this->createMock(Session::class);
        $mockSession->expects($this->once())
            ->method('updateDocument')
            ->with('doc-obj-123')
            ->willReturn($mockCommand);

        $document = $this->makeDocument($mockSession, 'doc-obj-123');

        $result = $document->updateProperties();

        $this->assertSame($mockCommand, $result);
    }

    // -------------------------------------------------------------------------
    // updateContent
    // -------------------------------------------------------------------------

    public function testUpdateContentDelegatesToSessionAndReturnsDocument(): void
    {
        $updatedDocument = $this->createMock(Document::class);

        $mockCommand = $this->createMock(SessionDocumentCommand::class);
        $mockCommand->method('execute')->willReturn($updatedDocument);

        $mockSession = $this->createMock(Session::class);
        $mockSession->expects($this->once())
            ->method('updateDocumentContent')
            ->with('doc-obj-123', 'test.txt', 'new content')
            ->willReturn($mockCommand);

        $document = $this->makeDocument($mockSession, 'doc-obj-123');

        $result = $document->updateContent('new content', 'test.txt');

        $this->assertSame($updatedDocument, $result);
    }

    // -------------------------------------------------------------------------
    // Getters / setters
    // -------------------------------------------------------------------------

    public function testGettersReturnSetValues(): void
    {
        $mockSession = $this->createMock(Session::class);

        $document = (new Document($mockSession))
            ->setObjectId('my-object-id')
            ->setName('report.pdf')
            ->setCreationDate(1700011111)
            ->setCreatedBy('alice');

        $this->assertSame('my-object-id', $document->getObjectId());
        $this->assertSame('report.pdf', $document->getName());
        $this->assertSame(1700011111, $document->getCreationDate());
        $this->assertSame('alice', $document->getCreatedBy());
    }

    public function testSettersReturnDocumentForChaining(): void
    {
        $mockSession = $this->createMock(Session::class);
        $document = new Document($mockSession);

        $this->assertInstanceOf(Document::class, $document->setObjectId('x'));
        $this->assertInstanceOf(Document::class, $document->setName('x'));
        $this->assertInstanceOf(Document::class, $document->setCreationDate(0));
        $this->assertInstanceOf(Document::class, $document->setCreatedBy('x'));
    }

    // -------------------------------------------------------------------------
    // getContent / getProperties — verify they issue a GET with the right params
    // -------------------------------------------------------------------------

    public function testGetContentIssuesGetWithContentSelector(): void
    {
        $mockHttpClient = $this->createMock(Client::class);
        $mockHttpClient->expects($this->once())
            ->method('get')
            ->willReturn(new Response(200, [], 'binary content here'));

        $mockRequest = $this->createMock(Request::class);
        $mockRequest->method('addUrlParameter')->willReturnSelf();

        $mockSession = $this->createMock(Session::class);
        $mockSession->method('request')->willReturn($mockRequest);
        $mockSession->method('getHttpClient')->willReturn($mockHttpClient);

        $document = $this->makeDocument($mockSession);

        $content = $document->getContent();

        $this->assertSame('binary content here', $content);
    }

    public function testGetPropertiesIssuesGetAndDecodesJson(): void
    {
        $properties = ['cmis:objectId' => ['value' => 'doc-obj-123']];

        $mockHttpClient = $this->createMock(Client::class);
        $mockHttpClient->expects($this->once())
            ->method('get')
            ->willReturn(new Response(200, [], json_encode($properties)));

        $mockRequest = $this->createMock(Request::class);
        $mockRequest->method('addUrlParameter')->willReturnSelf();

        $mockSession = $this->createMock(Session::class);
        $mockSession->method('request')->willReturn($mockRequest);
        $mockSession->method('getHttpClient')->willReturn($mockHttpClient);

        $document = $this->makeDocument($mockSession);

        $result = $document->getProperties();

        $this->assertSame($properties, $result);
    }
}
