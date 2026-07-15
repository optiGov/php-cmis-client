<?php

namespace CMIS\Http;

use CMIS\Http\Client;
use GuzzleHttp\Psr7\Response;

class Request
{

    private string $url;

    /**
     * CMIS properties of the request.
     *
     * @var array
     */
    private array $properties = [];

    /**
     * Post fields of the request.
     *
     * @var array
     */
    private array $postFields = [];

    /**
     * Url parameters of the request.
     *
     * @var array
     */
    private array $urlParameters = [];

    /**
     * Files of the request.
     *
     * @var array
     */
    private array $files = [];


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
        if (empty($this->urlParameters))
            return $this->url;

        $queryString = http_build_query($this->urlParameters);
        return $this->url . "?$queryString";
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
     * @param string $name
     * @param string $value
     * @return Request
     */
    public function addUrlParameter(string $name, string $value): static
    {
        $this->urlParameters[$name] = $value;
        return $this;
    }

    /**
     * @param string $id
     * @param string|array $value
     * @return Request
     */
    public function addProperty(string $id, string|array $value): static
    {
        $this->properties[$id] = $value;
        return $this;
    }

    /**
     * @param string $name
     * @param string $content
     * @param string $fileName
     * @return Request
     */
    public function addFile(string $name, string $content, string $fileName): static
    {
        $this->files[$name] = ["content" => $content, "filename" => $fileName];
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

        // add UTF-8 _charset_ default charset indicator
        if (!array_key_exists("_charset_", $postFields)) {
            $postFields = ["_charset_" => "UTF-8"] + $postFields;
        }

        // add properties
        $idx = 0;
        foreach ($this->properties as $id => $value) {
            $postFields["propertyId[$idx]"] = $id;
            if (is_array($value)) {
                foreach ($value as $valueIdx => $v) {
                    $postFields["propertyValue[$idx][$valueIdx]"] = $v;
                }
            } else {
                $postFields["propertyValue[$idx]"] = $value;
            }
            $idx++;
        }

        return $postFields;
    }

    /**
     * Returns whether the request has files or not.
     *
     * @return bool
     */
    public function hasFiles(): bool
    {
        return !empty($this->files);
    }

    /**
     * @return array
     */
    public function getFiles(): array
    {
        return $this->files;
    }

}