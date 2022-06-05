<?php

namespace CMIS\Entities;

class Folder
{
    /**
     * @var string
     */
    public string $objectId;

    /**
     * @var string
     */
    public string $name;

    /**
     * @var int
     */
    public int $creationDate;

    /**
     * @var string
     */
    public string $createdBy;

    /**
     * @var string
     */
    public string $path;

    /**
     * @var string
     */
    public string $parentId;

    /**
     * @return string
     */
    public function getObjectId(): string
    {
        return $this->objectId;
    }

    /**
     * @param string $objectId
     * @return Folder
     */
    public function setObjectId(string $objectId): Folder
    {
        $this->objectId = $objectId;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Folder
     */
    public function setName(string $name): Folder
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getCreationDate(): int
    {
        return $this->creationDate;
    }

    /**
     * @param int $creationDate
     * @return Folder
     */
    public function setCreationDate(int $creationDate): Folder
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedBy(): string
    {
        return $this->createdBy;
    }

    /**
     * @param string $createdBy
     * @return Folder
     */
    public function setCreatedBy(string $createdBy): Folder
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return Folder
     */
    public function setPath(string $path): Folder
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getParentId(): string
    {
        return $this->parentId;
    }

    /**
     * @param string $parentId
     * @return Folder
     */
    public function setParentId(string $parentId): Folder
    {
        $this->parentId = $parentId;
        return $this;
    }

}
