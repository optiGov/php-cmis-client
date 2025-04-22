<?php

namespace CMIS\Session;

class SessionFactory
{
    /**
     * Creates a new default Session for the Browser binding.
     *
     * @param string $url
     * @param string $repositoryId
     * @param string|null $user
     * @param string|null $password
     * @param string|null $bearerToken
     * @param SessionOptions|null $options
     * @return Session
     */
    public static function create(string $url, string $repositoryId, string $user = null, string $password = null, string $bearerToken = null, SessionOptions|null $options = null): Session
    {
        // create default session options
        $options ??= new SessionOptions();

        return (new Session())->setAuth($user, $password, $bearerToken)
            ->setUrl($url)
            ->setRepositoryId($repositoryId)
            ->setOptions($options)
            ->initialize();
    }
}