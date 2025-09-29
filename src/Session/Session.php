<?php

namespace CMIS\Session;

use CMIS\Http\Client;
use CMIS\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use CMIS\Http\RequestFactory;

class Session
{

    /**
     * CMIS user name.
     * @var string|null
     */
    private string|null $user = null;

    /**
     * CMIS password.
     * @var string|null
     */
    private string|null $password = null;

    /**
     * CMIS bearer token.
     * @var string|null
     */
    private string|null $bearerToken = null;

    /**
     * CMIS endpoint url.
     * @var string
     */
    private string $url;

    /**
     * CMIS repository id.
     * @var string
     */
    private string $repositoryId;

    /**
     * session options.
     * @var SessionOptions
     */
    private SessionOptions $options;

    /**
     * @var Client
     */
    private Client $httpClient;

    /**
     * Initializes the connection.
     *
     * @return $this
     */
    public function initialize(): static
    {
        $this->httpClient = (new Client())->setAuth($this->user, $this->password, $this->bearerToken)
            ->verifySSL($this->options->getOption("verify"))
            ->initialize();
        return $this;
    }

    /**
     * Creates a new document.
     *
     * @param string $name
     * @param string $cmisObjectTypeId
     * @param string $fileContent
     * @param string $cmisAction
     * @return SessionDocumentCommand
     */
    public function createDocument(string $name, string $cmisObjectTypeId, string $fileContent, string $cmisAction = "createDocument"): SessionDocumentCommand
    {
        return new SessionDocumentCommand(
            $this,
            RequestFactory::to($this->getRepositoryRootUrl())
                ->addPostField("cmisAction", $cmisAction)
                ->addProperty("cmis:objectTypeId", $cmisObjectTypeId)
                ->addProperty("cmis:name", $name)
                ->addFile("file", $fileContent, $name)
        );
    }

    /**
     * Creates a new folder.
     *
     * @param string $name
     * @param string $cmisObjectTypeId
     * @param string $cmisAction
     * @return SessionFolderCommand
     */
    public function createFolder(string $name, string $cmisObjectTypeId, string $cmisAction = "createFolder"): SessionFolderCommand
    {
        return new SessionFolderCommand(
            $this,
            RequestFactory::to($this->getRepositoryRootUrl())
                ->addPostField("cmisAction", $cmisAction)
                ->addProperty("cmis:objectTypeId", $cmisObjectTypeId)
                ->addProperty("cmis:name", $name)
        );
    }

    /**
     * Updates an existing document's content.
     *
     * @param string $objectId
     * @param string $fileContent
     * @param string $fileName
     * @return SessionDocumentUpdateCommand
     */
    public function updateDocument(string $objectId, string $fileContent, string $fileName): SessionDocumentUpdateCommand
    {
        return new SessionDocumentUpdateCommand(
            $this,
            RequestFactory::to($this->getRepositoryRootUrl())
                ->addPostField("cmisaction", "update")
                ->addPostField("objectId", $objectId)
                ->addFile("file", $fileContent, $fileName)
        );
    }

    /**
     * Creates a new request to a given url or the repository root url.
     *
     * @param string|null $to
     * @return Request
     */
    public function request(string $to = null): Request
    {
        return RequestFactory::to($to ?? $this->getRepositoryRootUrl());
    }

    /**
     * Returns the url to the repository root.
     *
     * @return string
     */
    private function getRepositoryRootUrl(): string
    {
        return "{$this->url}/{$this->repositoryId}/root";
    }

    /**
     * @return string|null
     */
    public function getUser(): ?string
    {
        return $this->user;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return string|null
     */
    public function getBearerToken(): ?string
    {
        return $this->bearerToken;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getRepositoryId(): string
    {
        return $this->repositoryId;
    }

    /**
     * @return Client
     */
    public function getHttpClient(): Client
    {
        return $this->httpClient;
    }

    /**
     * @param string $url
     * @return Session
     */
    public function setUrl(string $url): Session
    {
        $this->url = rtrim($url, '/');
        return $this;
    }

    /**
     * @param string|null $user
     * @param string|null $password
     * @param string|null $bearerToken
     * @return Session
     */
    public function setAuth(string $user = null, string $password = null, string $bearerToken = null): static
    {
        $this->user = $user;
        $this->password = $password;
        $this->bearerToken = $bearerToken;
        return $this;
    }

    /**
     * @param string $repositoryId
     * @return Session
     */
    public function setRepositoryId(string $repositoryId): Session
    {
        $this->repositoryId = $repositoryId;
        return $this;
    }

    /**
     * @param SessionOptions $options
     * @return Session
     */
    public function setOptions(SessionOptions $options): Session
    {
        $this->options = $options;
        return $this;
    }
}