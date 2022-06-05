<?php

namespace CMIS\Session;

use CMIS\Http\Client;
use CMIS\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use CMIS\Http\RequestFactory;
use GuzzleHttp\Psr7\Response;

class SessionCommand
{

    /**
     * @var Client
     */
    public Client $httpClient;

    /**
     * @var Request
     */
    public Request $request;

    /**
     * @param Client $httpClient
     * @param Request $request
     */
    public function __construct(Client $httpClient, Request $request)
    {
        $this->httpClient = $httpClient;
        $this->request = $request;
    }

    /**
     * @return Response
     * @throws GuzzleException
     */
    public function execute(): object
    {
        return $this->httpClient->post($this->request);
    }

    /**
     * @param string $name
     * @param string $value
     * @return SessionCommand
     */
    public function addPostField(string $name, string $value): static
    {
        $this->request->addPostField($name, $value);
        return $this;
    }

    /**
     * @param string $id
     * @param string $value
     * @return SessionCommand
     */
    public function addProperty(string $id, string $value): static
    {
        $this->request->addProperty($id, $value);
        return $this;
    }

}