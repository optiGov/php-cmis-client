<?php

namespace CMIS\Tests\Unit\Entities;

use CMIS\Entities\Folder;
use CMIS\Session\Session;
use CMIS\Session\SessionFolderCommand;
use PHPUnit\Framework\TestCase;

class FolderTest extends TestCase
{
    private function makeFolder(Session $session, string $objectId = 'folder-obj-456'): Folder
    {
        return (new Folder($session))
            ->setObjectId($objectId)
            ->setName('My Folder')
            ->setCreationDate(1700000000)
            ->setCreatedBy('john.doe')
            ->setPath('/root/my-folder')
            ->setParentId('parent-111');
    }

    // -------------------------------------------------------------------------
    // updateProperties (new)
    // -------------------------------------------------------------------------

    public function testUpdatePropertiesReturnsSessionFolderCommand(): void
    {
        $mockCommand = $this->createMock(SessionFolderCommand::class);

        $mockSession = $this->createMock(Session::class);
        $mockSession->expects($this->once())
            ->method('updateFolder')
            ->with('folder-obj-456')
            ->willReturn($mockCommand);

        $folder = $this->makeFolder($mockSession, 'folder-obj-456');

        $result = $folder->updateProperties();

        $this->assertSame($mockCommand, $result);
    }

    // -------------------------------------------------------------------------
    // Getters / setters
    // -------------------------------------------------------------------------

    public function testGettersReturnSetValues(): void
    {
        $mockSession = $this->createMock(Session::class);

        $folder = (new Folder($mockSession))
            ->setObjectId('folder-xyz')
            ->setName('Archive 2024')
            ->setCreationDate(1700022222)
            ->setCreatedBy('bob')
            ->setPath('/root/archive/2024')
            ->setParentId('root-parent-id');

        $this->assertSame('folder-xyz', $folder->getObjectId());
        $this->assertSame('Archive 2024', $folder->getName());
        $this->assertSame(1700022222, $folder->getCreationDate());
        $this->assertSame('bob', $folder->getCreatedBy());
        $this->assertSame('/root/archive/2024', $folder->getPath());
        $this->assertSame('root-parent-id', $folder->getParentId());
    }

    public function testSettersReturnFolderForChaining(): void
    {
        $mockSession = $this->createMock(Session::class);
        $folder = new Folder($mockSession);

        $this->assertInstanceOf(Folder::class, $folder->setObjectId('x'));
        $this->assertInstanceOf(Folder::class, $folder->setName('x'));
        $this->assertInstanceOf(Folder::class, $folder->setCreationDate(0));
        $this->assertInstanceOf(Folder::class, $folder->setCreatedBy('x'));
        $this->assertInstanceOf(Folder::class, $folder->setPath('/x'));
        $this->assertInstanceOf(Folder::class, $folder->setParentId('x'));
    }

    public function testNullableFieldsAcceptNull(): void
    {
        $mockSession = $this->createMock(Session::class);

        $folder = (new Folder($mockSession))
            ->setObjectId('folder-xyz')
            ->setName(null)
            ->setCreationDate(null)
            ->setCreatedBy(null)
            ->setPath(null)
            ->setParentId(null);

        $this->assertNull($folder->getName());
        $this->assertNull($folder->getCreationDate());
        $this->assertNull($folder->getCreatedBy());
        $this->assertNull($folder->getPath());
        $this->assertNull($folder->getParentId());
    }
}
