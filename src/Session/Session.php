<?php

namespace CMIS\Session;

use CMIS\Http\Client;
use GuzzleHttp\Exception\GuzzleException;
use CMIS\Http\RequestFactory;

class Session
{

    /**
     * CMIS user name.
     * @var string
     */
    private string $user;

    /**
     * CMIS password.
     * @var string
     */
    private string $password;

    /**
     * CMIS endpoint url.
     * @var string
     */
    private string $url;

    /**
     * CMIS reposirtory id.
     * @var string
     */
    private string $repositoryId;


    private Client $httpClient;

    /**
     * Initializes the connciton.
     *
     * @return $this
     */
    public function initialize(): static
    {
        $this->httpClient = (new Client())->setUser($this->user)
            ->setPassword($this->password)
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
     * @return SessionCommand
     */
    public function createDocument(string $name, string $cmisObjectTypeId, string $fileContent, string $cmisAction = "createDocument"): SessionCommand
    {
        return new SessionDocumentCommand(
            $this->httpClient,
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
            $this->httpClient,
            RequestFactory::to($this->getRepositoryRootUrl())
                ->addPostField("cmisAction", $cmisAction)
                ->addProperty("cmis:objectTypeId", $cmisObjectTypeId)
                ->addProperty("cmis:name", $name)
        );
    }

    /**
     * Returns the url to the repository root.
     *
     * @return string
     */
    private function getRepositoryRootUrl(): string
    {
        return  "{$this->url}/{$this->repositoryId}/root";
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
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
     * @param string $user
     * @return Session
     */
    public function setUser(string $user): Session
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param string $password
     * @return Session
     */
    public function setPassword(string $password): Session
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @param string $url
     * @return Session
     */
    public function setUrl(string $url): Session
    {
        $this->url = $url;
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
}