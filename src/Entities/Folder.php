<?php

namespace CMIS\Entities;

use CMIS\Session\Session;

class Folder
{
    /**
     * @var Session
     */
    private Session $session;

    /**
     * @var string
     */
    public string $objectId;

    /**
     * @var string|null
     */
    public string|null $name;

    /**
     * @var int|null
     */
    public int|null $creationDate;

    /**
     * @var string|null
     */
    public string|null $createdBy;

    /**
     * @var string|null
     */
    public string|null $path;

    /**
     * @var string|null
     */
    public string|null $parentId;

    /**
     * Creates a new Folder instance.
     *
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

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
     * @return string|null
     */
    public function getName(): string|null
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return Folder
     */
    public function setName(string|null $name): Folder
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCreationDate(): int|null
    {
        return $this->creationDate;
    }

    /**
     * @param int|null $creationDate
     * @return Folder
     */
    public function setCreationDate(int|null $creationDate): Folder
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCreatedBy(): string|null
    {
        return $this->createdBy;
    }

    /**
     * @param string|null $createdBy
     * @return Folder
     */
    public function setCreatedBy(string|null $createdBy): Folder
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPath(): string|null
    {
        return $this->path;
    }

    /**
     * @param string|null $path
     * @return Folder
     */
    public function setPath(string|null $path): Folder
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getParentId(): string|null
    {
        return $this->parentId;
    }

    /**
     * @param string|null $parentId
     * @return Folder
     */
    public function setParentId(string|null $parentId): Folder
    {
        $this->parentId = $parentId;
        return $this;
    }

}
