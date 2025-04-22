<?php

namespace CMIS\Http;

use CMIS\Session\Session;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;

class Client
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
        $options = ["verify" => $this->verifySSL];

        // if bearer token is set, use it for authentication
        if($this->bearerToken){
            $options["headers"] = [
                "Authorization" => "Bearer " . $this->bearerToken
            ];
        } else {
            // if user and password are set, use them for basic authentication
            if ($this->user && $this->password) {
                $options["auth"] = [
                    $this->user,
                    $this->password
                ];
            }
        }

        $this->httpClient = new \GuzzleHttp\Client($options);
        return $this;
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
     * @param string|null $user
     * @param string|null $password
     * @param string|null $bearerToken
     * @return Client
     */
    public function setAuth(string $user = null, string $password = null, string $bearerToken = null): static
    {
        $this->user = $user;
        $this->password = $password;
        $this->bearerToken = $bearerToken;
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
