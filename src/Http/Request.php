<?php

namespace CMIS\Http;

use CMIS\Http\Client;
use GuzzleHttp\Psr7\Response;

class Request
{

    private string $url;

    /**
     * CMIS properties of the request.
     * @var array
     */
    private array $properties = [];

    /**
     * Post fields of the request.
     * @var array
     */
    private array $postFields = [];


    /**
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $name
     * @param string $value
     * @return Request
     */
    public function addPostField(string $name, string $value): static
    {
        $this->postFields[$name] = $value;
        return $this;
    }

    /**
     * @param string $id
     * @param string $value
     * @return Request
     */
    public function addProperty(string $id, string $value): static
    {
        $this->properties[$id] = $value;
        return $this;
    }

    /**
     * Returns the post fields.
     *
     * @return array
     */
    public function getMergedPostFields(): array
    {
        // add post fields
        $postFields = $this->postFields;

        // add properties
        $idx = 0;
        foreach ($this->properties as $id => $value) {
            $postFields["propertyId[$idx]"] = $id;
            $postFields["propertyValue[$idx]"] = $value;
            $idx++;
        }
        
        return $postFields;
    }

}