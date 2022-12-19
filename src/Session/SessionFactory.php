<?php

namespace CMIS\Session;

class SessionFactory
{
    /**
     * Creates a new default Session for the Browser binding.
     *
     * @param string $user
     * @param string $password
     * @param string $url
     * @param string $repositoryId
     * @param SessionOptions|null $options
     * @return Session
     */
    public static function create(string $user, string $password, string $url, string $repositoryId, SessionOptions|null $options = null): Session
    {
        // create default session ooptions
        $options ??= new SessionOptions();

        return (new Session())->setUser($user)
            ->setPassword($password)
            ->setUrl($url)
            ->setRepositoryId($repositoryId)
            ->setOptions($options)
            ->initialize();
    }
}