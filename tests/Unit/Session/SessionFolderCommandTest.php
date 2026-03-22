<?php

namespace CMIS\Tests\Unit\Session;

use CMIS\Entities\Folder;

class SessionFolderCommandTest extends CmisCommandTestCase
{
    private function makeFolderResponse(
        string $objectId = 'folder-abc-123',
        string $name = 'My Folder',
        int $creationDate = 1700000000,
        string $createdBy = 'john.doe',
        string $parentId = 'parent-456',
        string $path = '/root/my-folder'
    ): \GuzzleHttp\Psr7\Response {
        return $this->makeCmisResponse([
            'cmis:objectId'     => ['value' => $objectId],
            'cmis:name'         => ['value' => $name],
            'cmis:creationDate' => ['value' => $creationDate],
            'cmis:createdBy'    => ['value' => $createdBy],
            'cmis:parentId'     => ['value' => $parentId],
            'cmis:path'         => ['value' => $path],
        ]);
    }

    // -------------------------------------------------------------------------

    public function testExecuteReturnsFolderInstance(): void
    {
        $folder = $this->makeFolderCommand($this->makeFolderResponse())->execute();

        $this->assertInstanceOf(Folder::class, $folder);
    }

    public function testExecuteMapsObjectId(): void
    {
        $folder = $this->makeFolderCommand($this->makeFolderResponse(objectId: 'folder-abc-123'))->execute();

        $this->assertSame('folder-abc-123', $folder->getObjectId());
    }

    public function testExecuteMapsName(): void
    {
        $folder = $this->makeFolderCommand($this->makeFolderResponse(name: 'Documents 2024'))->execute();

        $this->assertSame('Documents 2024', $folder->getName());
    }

    public function testExecuteMapsCreationDate(): void
    {
        $folder = $this->makeFolderCommand($this->makeFolderResponse(creationDate: 1700099999))->execute();

        $this->assertSame(1700099999, $folder->getCreationDate());
    }

    public function testExecuteMapsCreatedBy(): void
    {
        $folder = $this->makeFolderCommand($this->makeFolderResponse(createdBy: 'admin'))->execute();

        $this->assertSame('admin', $folder->getCreatedBy());
    }

    public function testExecuteMapsParentId(): void
    {
        $folder = $this->makeFolderCommand($this->makeFolderResponse(parentId: 'root-folder-789'))->execute();

        $this->assertSame('root-folder-789', $folder->getParentId());
    }

    public function testExecuteMapsPath(): void
    {
        $folder = $this->makeFolderCommand($this->makeFolderResponse(path: '/root/documents/2024'))->execute();

        $this->assertSame('/root/documents/2024', $folder->getPath());
    }
}
