<?php

namespace CMIS\Http;

class RequestFactory
{
    /**
     * Creates a new request.
     *
     * @param string $url
     * @return Request
     */
    public static function to(string $url): Request
    {
        return new Request($url);
    }
}