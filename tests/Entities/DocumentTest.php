<?php

namespace OptiGov\Tests\Entities;

use CMIS\Session\Session;
use CMIS\Session\SessionFactory;
use PHPUnit\Framework\TestCase;

class DocumentTest extends TestCase
{
    private Session $session;

    protected function setUp(): void
    {
        $this->session = SessionFactory::create(
            CMIS_USER,
            CMIS_PASSWORD,
            CMIS_URL,
            CMIS_REPOSITORY_ID
        );
    }

    /**
     * Tests ->createDocument()
     */
    public function testCreateDocument()
    {
        $response = $this->session
            ->createDocument(
                "Bewohnerparkausweis.txt",
                CMIS_OBJECT_TYPE_ID_DOCUMENT,
                "Dummy Content")
            ->addProperty("repo[355]", "A3") // auftrag id
            ->execute();

        $this->assertSame(
            200,
            $response->getStatusCode()
        );
    }


}