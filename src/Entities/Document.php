<?php

namespace CMIS\Entities;

class Document
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
     * @return string
     */
    public function getObjectId(): string
    {
        return $this->objectId;
    }

    /**
     * @param string $objectId
     * @return Document
     */
    public function setObjectId(string $objectId): Document
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
     * @return Document
     */
    public function setName(string $name): Document
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
     * @return Document
     */
    public function setCreationDate(int $creationDate): Document
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
     * @return Document
     */
    public function setCreatedBy(string $createdBy): Document
    {
        $this->createdBy = $createdBy;
        return $this;
    }

}
