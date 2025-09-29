<?php

namespace CMIS\Entities;

use CMIS\Session\Session;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;

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
     * @return string|null
     */
    public function getName(): string|null
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return Document
     */
    public function setName(string|null $name): Document
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
     * @return Document
     */
    public function setCreationDate(int|null $creationDate): Document
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
     * @return Document
     */
    public function setCreatedBy(string|null $createdBy): Document
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
        // get the content and return as string
        $response = $this->getCMISData($cmisSelector);
        return (string)$response->getBody();
    }

    /**
     * Returns the properties of the document.
     *
     * @param string $cmisSelector
     * @return array
     * @throws GuzzleException
     */
    public function getProperties(string $cmisSelector = "properties"): array
    {
        // get the content and return as string
        $response = $this->getCMISData($cmisSelector);
        return json_decode((string)$response->getBody(), true);
    }

    /**
     * Updates the content of this document.
     *
     * @param string $fileContent
     * @param string $fileName
     * @return Document
     * @throws GuzzleException
     */
    public function updateContent(string $fileContent, string $fileName): Document
    {
        return $this->session->updateDocument($this->objectId, $fileContent, $fileName)->execute();
    }

    /**
     * Performs a GET call against the CMIS api with a given CMIS selector.
     *
     * @param string $cmisSelector
     * @return Response
     * @throws GuzzleException
     */
    public function getCMISData(string $cmisSelector): Response
    {
        // build the resource url
        $request = $this->session->request()
            ->addUrlParameter("objectId", $this->objectId)
            ->addUrlParameter("cmisselector", $cmisSelector);

        return $this->session->getHttpClient()->get($request);
    }

}
