<?php

namespace CMIS\Tests\Unit\Session;

use CMIS\Entities\Document;
use CMIS\Session\SessionDocumentCommand;

class SessionDocumentCommandTest extends CmisCommandTestCase
{
    private function makeDocumentResponse(
        string $objectId = 'doc-abc-123',
        string $name = 'test.txt',
        int $creationDate = 1700000000,
        string $createdBy = 'john.doe'
    ): \GuzzleHttp\Psr7\Response {
        return $this->makeCmisResponse([
            'cmis:objectId'     => ['value' => $objectId],
            'cmis:name'         => ['value' => $name],
            'cmis:creationDate' => ['value' => $creationDate],
            'cmis:createdBy'    => ['value' => $createdBy],
        ]);
    }

    // -------------------------------------------------------------------------

    public function testExecuteReturnsDocumentInstance(): void
    {
        $document = $this->makeDocumentCommand($this->makeDocumentResponse())->execute();

        $this->assertInstanceOf(Document::class, $document);
    }

    public function testExecuteMapsObjectId(): void
    {
        $document = $this->makeDocumentCommand($this->makeDocumentResponse(objectId: 'doc-abc-123'))->execute();

        $this->assertSame('doc-abc-123', $document->getObjectId());
    }

    public function testExecuteMapsName(): void
    {
        $document = $this->makeDocumentCommand($this->makeDocumentResponse(name: 'my-file.pdf'))->execute();

        $this->assertSame('my-file.pdf', $document->getName());
    }

    public function testExecuteMapsCreationDate(): void
    {
        $document = $this->makeDocumentCommand($this->makeDocumentResponse(creationDate: 1700012345))->execute();

        $this->assertSame(1700012345, $document->getCreationDate());
    }

    public function testExecuteMapsCreatedBy(): void
    {
        $document = $this->makeDocumentCommand($this->makeDocumentResponse(createdBy: 'jane.doe'))->execute();

        $this->assertSame('jane.doe', $document->getCreatedBy());
    }

    public function testAddPropertyChainReturnsSameCommand(): void
    {
        $command = $this->makeDocumentCommand($this->makeDocumentResponse());

        $this->assertSame($command, $command->addProperty('repo:tags', 'value'));
    }

    public function testAddArrayPropertyChainReturnsSameCommand(): void
    {
        $command = $this->makeDocumentCommand($this->makeDocumentResponse());

        $this->assertSame($command, $command->addProperty('repo:tags', ['a', 'b']));
    }

    public function testAddPostFieldChainReturnsSameCommand(): void
    {
        $command = $this->makeDocumentCommand($this->makeDocumentResponse());

        $this->assertSame($command, $command->addPostField('objectId', 'parent-123'));
    }
}
