<?php

namespace OptiGov\Tests\Entities;

use CMIS\Entities\Folder;
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
     * Tests ->createFolder()
     */
    public function testCreateFolder()
    {
        $folder = $this->session
            ->createFolder("Bewohnerparkausweis | Jane Doe", CMIS_OBJECT_TYPE_ID_FOLDER)
            ->addPostField("objectId", CMIS_REPOSITORY_ID)
            ->addProperty("repo[304]", "D1") // dienstleistung id
            ->addProperty("repo[303]", "B2") // buerger id
            ->addProperty("repo[355]", "A4") // auftrag id
            ->addProperty("odf:portalname", "optiGov Core")
            ->addProperty("odf:hersteller", "optiGov")
            ->addProperty("odf:portalid", "oG-localhost")
            ->addProperty("odf:queryid", "oG-folder-1")
            ->addProperty("odf:creationdate", "01.01.2022 08:30")
            ->addProperty("odf:lastupdate", "01.01.2022 09:45")
            ->addProperty("odf:authlevel", "hoch")
            ->addProperty("odf:auftragsid", "1")
            ->execute();

        $this->assertInstanceOf(
            Folder::class,
            $folder
        );
    }


}