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
     * @return Session
     */
    public static function create(string $user, string $password, string $url, string $repositoryId): Session
    {
        return (new Session())->setUser($user)
            ->setPassword($password)
            ->setUrl($url)
            ->setRepositoryId($repositoryId)
            ->initialize();
    }
}