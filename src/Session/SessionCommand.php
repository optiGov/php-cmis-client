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
     * @var Session
     */
    protected Session $session;

    /**
     * @var Client
     */
    protected Client $httpClient;

    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @param Client $httpClient
     * @param Request $request
     */
    public function __construct(Session $session, Request $request)
    {
        $this->session = $session;
        $this->httpClient = $session->getHttpClient();
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
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
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
     * @param string|array $value
     * @return SessionCommand
     */
    public function addProperty(string $id, string|array $value): static
    {
        $this->request->addProperty($id, $value);
        return $this;
    }

}