<?php

namespace OptiGov\Tests\Responsibilities;

use CMIS\Session\Session;
use CMIS\Session\SessionFactory;
use PHPUnit\Framework\TestCase;

class FolderTest extends TestCase
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
     * Tests ->alleAntraege()
     */
    public function testAlleAntraege()
    {
        $response = $this->session
            ->createFolder("Bewohnerparkausweis | Jane Doe")
            ->addPostField("objectId", "54c7bf80-f4b2-561a-9656-6ccfdc27ae3d")
            ->addProperty("repo[304]", "D1") // dienstleistung id
            ->addProperty("repo[303]", "B2") // buerger id
            ->addProperty("repo[355]", "A3") // auftrag id
            ->addProperty("odf:portalname", "optiGov Core")
            ->addProperty("odf:hersteller", "optiGov")
            ->addProperty("odf:portalid", "oG-localhost")
            ->addProperty("odf:queryid", "oG-folder-1")
            ->addProperty("odf:creationdate", "10.01.2022 15:30")
            ->addProperty("odf:lastupdate", "10.01.2022 15:45")
            ->addProperty("odf:authlevel", "hoch")
            ->addProperty("odf:auftragsid", "1")
            ->addProperty("odf:objecttype", "ONAVO")
            ->execute();

        $this->assertSame(
            200,
            $response->getStatusCode()
        );
    }


}