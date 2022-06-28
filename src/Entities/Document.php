<?php

namespace CMIS\Entities;

use CMIS\Session\Session;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use http\Env\Request;

class Document
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
     * Creates a new Document instance.
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

    /**
     * Downloads the content from the CMIS server.
     *
     * @param string $cmisSelector
     * @return string
     * @throws GuzzleException
     */
    public function getContent(string $cmisSelector = "content"): string
    {
        // build the resource url
        $request = $this->session->request()
            ->addUrlParameter("objectId", $this->objectId)
            ->addUrlParameter("cmisselector", $cmisSelector);

        // return the response
        return (string) $this->session->getHttpClient()->get($request)->getBody();
    }

}
