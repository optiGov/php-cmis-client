<?php

namespace CMIS\Tests\Entities;

use CMIS\Entities\Document;
use CMIS\Session\Session;
use CMIS\Session\SessionFactory;
use PHPUnit\Framework\TestCase;

class DocumentUpdateTest extends TestCase
{
    private Session $session;

    protected function setUp(): void
    {
        $this->session = SessionFactory::create(
            CMIS_URL,
            CMIS_REPOSITORY_ID,
            CMIS_USER,
            CMIS_PASSWORD
        );
    }

    public function testUpdateDocumentContent(): void
    {
        // First create a document
        $originalContent = "Original content";
        $updatedContent = "Updated content";
        $fileName = "test-update-" . time() . ".txt";

        $document = $this->session->createDocument(
            $fileName,
            CMIS_OBJECT_TYPE_ID_DOCUMENT,
            $originalContent
        )->execute();

        $this->assertInstanceOf(Document::class, $document);
        $this->assertNotEmpty($document->getObjectId());

        // Update the document content
        $updatedDocument = $document->updateContent($updatedContent, $fileName);

        $this->assertInstanceOf(Document::class, $updatedDocument);
        $this->assertEquals($document->getObjectId(), $updatedDocument->getObjectId());

        // Verify the content was updated
        $retrievedContent = $updatedDocument->getContent();
        $this->assertEquals($updatedContent, $retrievedContent);
    }

    public function testUpdateDocumentViaSession(): void
    {
        // First create a document
        $originalContent = "Original content via session";
        $updatedContent = "Updated content via session";
        $fileName = "test-session-update-" . time() . ".txt";

        $document = $this->session->createDocument(
            $fileName,
            CMIS_OBJECT_TYPE_ID_DOCUMENT,
            $originalContent
        )->execute();

        $this->assertInstanceOf(Document::class, $document);
        $this->assertNotEmpty($document->getObjectId());

        // Update the document content using session method
        $updatedDocument = $this->session->updateDocumentContent(
            $document->getObjectId(),
            $updatedContent,
            $fileName
        )->execute();

        $this->assertInstanceOf(Document::class, $updatedDocument);
        $this->assertEquals($document->getObjectId(), $updatedDocument->getObjectId());

        // Verify the content was updated
        $retrievedContent = $updatedDocument->getContent();
        $this->assertEquals($updatedContent, $retrievedContent);
    }
}