<?php

namespace CMIS\Http;

use CMIS\Session\Session;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;

class Client
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
     * SSL connection verification.
     * @var bool
     */
    private bool $verifySSL = true;

    /**
     * @var \GuzzleHttp\Client
     */
    private \GuzzleHttp\Client $httpClient;


    /**
     * @return $this
     */
    public function initialize(): static
    {
        $this->httpClient = new \GuzzleHttp\Client(
            [
                "verify" => $this->verifySSL,
                "auth" => [
                    $this->user,
                    $this->password
                ]
            ]
        );
        return $this;
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
     * @param string $user
     * @return Client
     */
    public function setUser(string $user): static
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return \GuzzleHttp\Client
     */
    public function getHttpClient(): \GuzzleHttp\Client
    {
        return $this->httpClient;
    }

    /**
     * @param string $password
     * @return Client
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return bool
     */
    public function SSLisVerified(): bool
    {
        return $this->verifySSL;
    }

    /**
     * @param bool $verifySSL
     * @return Client
     */
    public function verifySSL(bool $verifySSL): static
    {
        $this->verifySSL = $verifySSL;
        return $this;
    }

    /**
     * Submits a post request.
     *
     * @param Request $request
     * @return Response
     * @throws GuzzleException
     */
    public function post(Request $request): Response
    {
        // send request if it is a default form post request
        if (!$request->hasFiles())
            return $this->httpClient->post($request->getUrl(), ["form_params" => $request->getMergedPostFields()]);

        // send request as multipart form request, as request has files
        $multipartData = [];
        $data = array_merge($request->getMergedPostFields(), $request->getFiles());

        // add files and default fields to multipart data
        foreach ($data as $name => $content) {
            if (!is_array($content)) {
                $multipartData[] = [
                    "name" => $name,
                    "contents" => $content
                ];
            } else {
                $multipartData[] = [
                    "name" => $name,
                    "contents" => $content["content"],
                    "filename" => $content["filename"],
                ];
            }
        }

        return $this->httpClient->post($request->getUrl(), ["multipart" => $multipartData]);
    }

    /**
     * Submites a get request.
     *
     * @param Request $request
     * @return Response
     * @throws GuzzleException
     */
    public function get(Request $request): Response
    {
        return $this->httpClient->get($request->getUrl());
    }
}
